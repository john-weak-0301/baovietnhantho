<?php

namespace App\Http\Composers;

use App\Utils\MenuFactory;
use Lavary\Menu\Builder;
use Lavary\Menu\Facade as Menu;
use Lavary\Menu\Item;

class HeaderMenuComposer
{
    public function compose(): void
    {
        Menu::make('headerMenu', function (Builder $menu) {
            $newMenuEnabled = (bool) setting('enable_new_menu', false);

            if ($newMenuEnabled) {
                MenuFactory::fromBuilder($menu, 'main', function (Item $menuItem, $rawItem) {
                    $hasChild = $menuItem->children()->count() > 0;
                    $hasMegaMenu = !$hasChild && (int) $rawItem->parent === 0 && $rawItem->content;

                    if ($hasMegaMenu) {
                        $menuItem->attr(['class' => ' menu-item-has-children menu-item-megamenu']);
                        $menuItem->append('<i class="fa fa-angle-down"></i>');
                        $menuItem->after(view('header.megamenu', compact('menuItem', 'rawItem')));
                    } elseif ($hasChild) {
                        $menuItem->attr(['class' => ' menu-item-has-children menu-item-menulist']);
                        $menuItem->append('<i class="fa fa-angle-down"></i>');
                    }
                });

                return;
            }

            $menu->add(
                __('Mục tiêu của bạn'),
                [
                    'url'   => '#lap-ke-hoach',
                    'class' => 'menu-item-has-children menu-item-megamenu',
                ])
                ->append('<i class="fa fa-angle-down"></i>')
                ->after(view('header.solution'));

            $menu->add(__('Sản phẩm'),
                [
                    'url'   => 'san-pham',
                    'class' => 'menu-item-has-children menu-item-megamenu',
                ])
                ->active('san-pham/*')
                ->append('<i class="fa fa-angle-down"></i>')
                ->after(view('header.product'));

            $menu->add(__('Dịch vụ khách hàng'),
                [
                    'url'   => 'dich-vu-khach-hang',
                    'class' => 'menu-item-has-children menu-item-megamenu',
                ])
                ->active('dich-vu-khach-hang/*')
                ->append('<i class="fa fa-angle-down"></i>')
                ->after(view('header.service'));

            $menu->add(__('Góc chuyên gia'), route('exps.home'))
                ->active('goc-chuyen-gia/*');

            $menu->add(__('Bảo việt nhân thọ'),
                [
                    'url'   => 'bao-viet-nhan-tho',
                    'class' => 'menu-item-has-children menu-item-megamenu',
                ])
                ->append('<i class="fa fa-angle-down"></i>')
                ->after(view('header.baoVietLife'));

            $menu->add(__('Tin tức'), route('news.home'))
                ->active('tin-tuc/*');
        });
    }
}
