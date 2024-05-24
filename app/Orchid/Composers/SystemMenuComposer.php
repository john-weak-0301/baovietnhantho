<?php

namespace App\Orchid\Composers;

use Orchid\Platform\Menu;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Dashboard;

class SystemMenuComposer
{
    /**
     * @var Dashboard
     */
    private $dashboard;

    /**
     * MenuComposer constructor.
     *
     * @param  Dashboard  $dashboard
     */
    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    /**
     * Registering the main menu items.
     */
    public function compose(): void
    {
        $this->dashboard->menu
            ->add(
                Menu::SYSTEMS,
                ItemMenu::label(__('Access rights'))
                    ->icon('icon-lock')
                    ->slug('Auth')
                    ->active('platform.systems.*')
                    ->permission('platform.systems')
                    ->sort(1000)
            )
            ->add(
                'Auth',
                ItemMenu::label(__('Users'))
                    ->icon('icon-user')
                    ->route('platform.systems.users')
                    ->permission('platform.systems.users')
                    ->sort(1000)
                    ->title(__('All registered users'))
            )
            ->add(
                'Auth',
                ItemMenu::label(__('Roles'))
                    ->icon('icon-lock')
                    ->route('platform.systems.roles')
                    ->permission('platform.systems.roles')
                    ->sort(1000)
                    ->title(__('A Role defines a set of tasks a user assigned the role is allowed to perform.'))
            );

        $this->dashboard->menu
            ->add(Menu::SYSTEMS,
                ItemMenu::label(__('Option'))
                    ->icon('icon-options')
                    ->slug('Option')
                    ->active('platform.systems.*')
                    ->permission('platform.systems')
                    ->sort(1000)
            )
            ->add(
                'Option',
                ItemMenu::label(__('Menu'))
                    ->icon('icon-menu')
                    ->route('systems.menu.index')
                    ->permission('platform.systems.menu')
                    ->show(count(config('press.menu', [])) > 0)
                    ->title(__('Editing of a custom menu (navigation) using drag & drop and localization support.'))
            )
            ->add('Option',
                ItemMenu::label(__('Options'))
                    ->icon('icon-user')
                    ->route('platform.systems.options')
                    ->permission('platform.systems.options')
                    ->sort(1000)
                    ->title(__('All registered options'))
            );
    }
}
