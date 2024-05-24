<?php

namespace Core\Dashboard\Composers;

use Orchid\Platform\Menu;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Dashboard;

class SystemMenuComposer
{
    /* Constants */
    public const MENU_MANAGER = 'manager';

    /**
     * @var Dashboard
     */
    private $dashboard;

    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function compose(): void
    {
        $this->composeManagerMenu();
    }

    protected function composeManagerMenu(): void
    {
        $this->dashboard->menu
            ->add(
                Menu::SYSTEMS,
                ItemMenu::label(__('Quản lý'))
                    ->icon('icon-lock')
                    ->slug(self::MENU_MANAGER)
                    ->active('platform.systems.*')
                    ->permission('platform.systems')
                    ->sort(15000)
            )
            ->add(
                self::MENU_MANAGER,
                ItemMenu::label(__('Thư viện'))
                    ->icon('icon-picture')
                    ->route('dashboard.media')
                    // ->permission('platform.systems.media')
                    ->title(__('Quản lý media trong hệ thống.'))
            )
            ->add(
                self::MENU_MANAGER,
                ItemMenu::label(__('Quản lý thư mục'))
                    ->icon('icon-folder-alt')
                    ->route('platform.systems.media')
                    ->show(false)
                    // ->permission('platform.systems.media')
                    ->title(__('Quản lý các tập tin và thư mục trong hệ thống.'))
            );
    }
}
