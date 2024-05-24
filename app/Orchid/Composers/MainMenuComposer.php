<?php

namespace App\Orchid\Composers;

use App\Model\Branch;
use App\Model\BranchService;
use App\Model\Category;
use App\Model\Consultant;
use App\Model\Contact;
use App\Model\Counselor;
use App\Model\Experience;
use App\Model\ExperienceCategory;
use App\Model\News;
use App\Model\Personality;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\Service;
use App\Model\Fund;
use App\Model\Page;
use App\Model\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Orchid\Platform\Menu;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Dashboard;

class MainMenuComposer
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
    public function compose()
    {
        $user = Auth::user();

        // Profile
        $this->dashboard->menu
            ->add(
                Menu::PROFILE,
                ItemMenu::label(__('Xem trang chủ'))
                    ->url('/')
                    ->icon('icon-home')
            );

        // Main
        $this->dashboard->menu
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Trang chủ'))
                    ->icon('icon-home')
                    ->route('platform.home')
                    ->title(__('Trang'))
                    ->sort(10)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Các trang khác'))
                    ->icon('icon-book-open')
                    ->route('platform.pages')
                    ->slug('platform.pages')
                    ->canSee($user->can(Page::PERMISSION_VIEW))
                    ->sort(10)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Danh mục sản phẩm'))
                    ->icon('icon-list')
                    ->route('platform.products.categories')
                    ->title(__('Sản phẩm'))
                    ->canSee($user->can(ProductCategory::PERMISSION_VIEW))
                    ->sort(20)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Sản phẩm'))
                    ->icon('icon-notebook')
                    ->route('platform.products')
                    ->canSee($user->can(Product::PERMISSION_VIEW))
                    ->sort(20)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Sản phẩm bổ trợ'))
                    ->icon('icon-notebook')
                    ->route('platform.addition_products')
                    ->canSee($user->can(Product::PERMISSION_VIEW))
                    ->sort(20)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Dịch vụ'))
                    ->icon('icon-notebook')
                    ->route('platform.services')
                    ->title(__('Dịch vụ khách hàng'))
                    ->slug('services')
                    ->canSee($user->can(Service::PERMISSION_VIEW))
                    ->sort(30)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Danh mục'))
                    ->icon('icon-list')
                    ->slug('services-categories')
                    ->route('platform.services_categories')
                    ->canSee($user->can(ServiceCategory::PERMISSION_VIEW))
                    ->sort(30)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Quản lý quỹ'))
                    ->icon('icon-notebook')
                    ->route('platform.funds')
                    ->title(__('Quỹ liên kết'))
                    ->slug('funds')
                    ->canSee($user->can(Fund::PERMISSION_VIEW))
                    ->sort(30)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Kinh nghiệm'))
                    ->icon('icon-notebook')
                    ->route('platform.exps')
                    ->title(__('Góc chuyên gia'))
                    ->canSee($user->can(Experience::PERMISSION_VIEW))
                    ->sort(40)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Danh mục'))
                    ->icon('icon-list')
                    ->route('platform.exps_categories')
                    ->slug('platform.exps_categories')
                    ->canSee($user->can(ExperienceCategory::PERMISSION_VIEW))
                    ->sort(40)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Tin tức'))
                    ->icon('icon-pin')
                    ->route('platform.news')
                    ->title(__('Tin tức'))
                    ->canSee($user->can(News::PERMISSION_VIEW))
                    ->sort(50)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Danh mục'))
                    ->icon('icon-list')
                    ->route('platform.categories')
                    ->slug('platform.categories')
                    ->canSee($user->can(Category::PERMISSION_VIEW))
                    ->sort(50)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Chi nhánh'))
                    ->icon('icon-home')
                    ->route('platform.branchs')
                    ->title(__('Điểm giao dịch'))
                    ->canSee($user->can(Branch::PERMISSION_VIEW))
                    ->sort(60)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Dịch vụ'))
                    ->icon('icon-notebook')
                    ->route('platform.branchs.services')
                    ->slug('platform.branchs.services')
                    ->canSee($user->can(BranchService::PERMISSION_VIEW))
                    ->sort(60)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Nhân viên tư vấn'))
                    ->icon('icon-user')
                    ->route('platform.counselors')
                    ->title(__('Nhân viên tư vấn'))
                    ->canSee($user->can(Counselor::PERMISSION_VIEW))
                    ->sort(70)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Tính cách'))
                    ->icon('icon-list')
                    ->route('platform.personalities')
                    ->canSee($user->can(Personality::PERMISSION_VIEW))
                    ->sort(70)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Tư vấn khách hàng'))
                    ->icon('icon-friends')
                    ->route('platform.consultants')
                    ->canSee($user->can(Consultant::PERMISSION_VIEW))
                    ->sort(70)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Liên hệ'))
                    ->icon('icon-user')
                    ->route('platform.contacts')
                    ->canSee($user->can(Contact::PERMISSION_VIEW))
                    ->sort(70)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Người dùng'))
                    ->title(__('Cấu hình hệ thống'))
                    ->icon('icon-user')
                    ->route('platform.systems.users')
                    ->permission('platform.systems.users')
                    ->sort(1000)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Quản lý Media'))
                    ->icon('icon-picture')
                    ->route('dashboard.media')
                    ->sort(1000)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Menu'))
                    ->icon('icon-menu')
                    ->slug('menus')
                    ->route('systems.menu.index')
                    ->permission('platform.systems.menu')
                    ->canSee(count(config('press.menu', [])) > 0)
                    ->sort(1000)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Quản lý Blocks'))
                    ->icon('icon-module')
                    ->route('dashboard.blocks')
                    ->sort(1000)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Quản lý Popup'))
                    ->icon('icon-browser')
                    ->route('platform.popups')
                    ->sort(1000)
            )
            ->add(
                Menu::MAIN,
                ItemMenu::label(__('Cài đặt'))
                    ->icon('icon-options')
                    ->route('platform.systems.options')
                    ->permission('platform.systems.options')
                    ->sort(1000)
            );
    }
}
