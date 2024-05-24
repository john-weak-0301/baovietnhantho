<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Collection;
use Lavary\Menu\Builder;
use Lavary\Menu\Facade as Menu;
use Core\Dashboard\Models\Menu as MenuModel;
use Lavary\Menu\Item;

class MenuFactory
{
    /**
     * //
     *
     * @param  string  $name
     * @param  string|null  $menu
     * @return Builder
     */
    public static function make(string $name, string $menu = null)
    {
        return Menu::make($menu ?: $name, function (Builder $builder) use ($name) {
            static::buildMenuItems(
                static::getRawMenuItems($name),
                $builder,
                $name.'-'
            );
        });
    }

    public static function fromBuilder(Builder $builder, string $menu, callable $callback = null)
    {
        static::buildMenuItems(
            static::getRawMenuItems($menu),
            $builder,
            $menu . '-',
            $callback
        );
    }

    /**
     * @param Collection $items
     * @param Builder|Item $builder
     * @param string $prefix
     * @param callable|null $callback
     */
    protected static function buildMenuItems($items, $builder, $prefix = '', callable $callback = null)
    {
        foreach ($items as $item) {
            $menu = $builder->add($item->label, [
                'id'    => $prefix.$item->id,
                'url'   => url($item->slug),
                'class' => $item->style,
            ]);

            $menu->link->attr(['title' => $item->title]);

            if ($item->target === '_blank') {
                $menu->link->attr(['target' => '_blank']);
            }

            if ($item->children) {
                static::buildMenuItems($item->children, $menu, $prefix);
            }

            if ($callback) {
                $callback($menu, $item);
            }
        }
    }

    /**
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRawMenuItems(string $name)
    {
        return cache()->rememberForever('menus.'.$name, function () use ($name) {
            return MenuModel::query()
                ->where('parent', 0)
                ->where('type', $name)
                ->orderBy('sort')
                ->with('children')
                ->get();
        });
    }
}
