<?php

declare(strict_types=1);

use App\Model\News;
use App\Model\Page;
use App\Model\Fund;
use App\Model\FundImport;
use App\Model\Product;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Trail;

// Platform > System > Users
Breadcrumbs::for('platform.systems.users', function (Trail $trail) {
    $trail->parent('platform.systems.index');
    $trail->push(__('Người dùng'), route('platform.systems.users'));
});

// Platform > System > Options
Breadcrumbs::for('platform.systems.options', function (Trail $trail) {
    $trail->parent('platform.systems.index');
    $trail->push(__('Options'), route('platform.systems.options'));
});

// Platform > System > Users > User
Breadcrumbs::for('platform.systems.users.edit', function (Trail $trail, $user) {
    $trail->parent('platform.systems.users');
    $trail->push(__('Sửa'), route('platform.systems.users.edit', $user));
});

// Platform > System > Roles
Breadcrumbs::for('platform.systems.roles', function (Trail $trail) {
    $trail->parent('platform.systems.index');
    $trail->push(__('Roles'), route('platform.systems.roles'));
});

// Platform > System > Roles > Create
Breadcrumbs::for('platform.systems.roles.create', function (Trail $trail) {
    $trail->parent('platform.systems.roles');
    $trail->push(__('Create'), route('platform.systems.roles.create'));
});

// Platform > System > Roles > Role
Breadcrumbs::for('platform.news.edit', function (Trail $trail, News $news) {
    $trail->parent('platform.news');
    $trail->push($news->title, $news->url->edit);
});

// Platform > Pages
Breadcrumbs::for('platform.pages', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Trang'), route('platform.pages'));
});

// Platform > Pages > Create Page
Breadcrumbs::for('platform.pages.create', function (Trail $trail) {
    $trail->parent('platform.pages');
    $trail->push(__('Thêm trang'), route('platform.news.create'));
});

// Platform > News > {Page Title}
Breadcrumbs::for('platform.pages.edit', function (Trail $trail, $page) {
    $trail->parent('platform.pages');

    $trail->push($page->title ?: '', route('platform.pages.edit', $page));
});

// Platform > News
Breadcrumbs::for('platform.news', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Tin tức'), route('platform.news'));
});

// Platform > News > Create News
Breadcrumbs::for('platform.news.create', function (Trail $trail) {
    $trail->parent('platform.news');
    $trail->push(__('Thêm tin tức'), route('platform.news.create'));
});

// Platform > Services
Breadcrumbs::for('platform.services', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Dịch vụ'), route('platform.services'));
});

// Platform > Services > Create Service
Breadcrumbs::for('platform.services.create', function (Trail $trail) {
    $trail->parent('platform.services');
    $trail->push(__('Thêm dịch vụ'), route('platform.services.create'));
});

// Platform > Services > {Service Title}
Breadcrumbs::for('platform.services.edit', function (Trail $trail, $service) {
    $trail->parent('platform.services');
    $trail->push($service->title, route('platform.services.edit', $service));
});

// Platform > Categories
Breadcrumbs::for('platform.categories', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Danh mục'), route('platform.categories'));
});

// Platform > Services > Create Category
Breadcrumbs::for('platform.categories.create', function (Trail $trail) {
    $trail->parent('platform.categories');
    $trail->push(__('Thêm danh mục'), route('platform.categories.create'));
});

// Platform > Categories > {Category Name}
Breadcrumbs::for('platform.categories.edit', function (Trail $trail, $category) {
    $trail->parent('platform.categories');
    $trail->push($category->name, route('platform.categories.edit', $category));
});

// Platform > Counselors
Breadcrumbs::for('platform.counselors', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Nhân viên tư vấn'), route('platform.counselors'));
});

// Platform > Services > Create Counselor
Breadcrumbs::for('platform.counselors.create', function (Trail $trail) {
    $trail->parent('platform.counselors');
    $trail->push(__('Thêm nhân viên'), route('platform.counselors.create'));
});

// Platform > Categories > {Counselor Name}
Breadcrumbs::for('platform.counselors.edit', function (Trail $trail, $counselor) {
    $trail->parent('platform.counselors');
    $trail->push($counselor->display_name, route('platform.counselors.edit', $counselor));
});

// Platform > Personalities
Breadcrumbs::for('platform.personalities', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Tính cách'), route('platform.personalities'));
});

// Platform > Services > Create Personality
Breadcrumbs::for('platform.personalities.create', function (Trail $trail) {
    $trail->parent('platform.categories');
    $trail->push(__('Thêm tính cách'), route('platform.categories.create'));
});

// Platform > Personalities > {Personality Name}
Breadcrumbs::for('platform.personalities.edit', function (Trail $trail, $personality) {
    $trail->parent('platform.personalities');
    $trail->push($personality->name, route('platform.personalities.edit', $personality));
});

// Platform > Categories
Breadcrumbs::for('platform.products.categories', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Danh mục sản phẩm'), route('platform.products.categories'));
});

// Platform > Services > Create Category
Breadcrumbs::for('platform.products.categories.create', function (Trail $trail) {
    $trail->parent('platform.products.categories');
    $trail->push(__('Thêm danh mục'), route('platform.products.categories.create'));
});

// Platform > Categories > {Category Name}
Breadcrumbs::for('platform.products.categories.edit', function (Trail $trail, $category) {
    $trail->parent('platform.products.categories');
    $trail->push($category->name, route('platform.products.categories.edit', $category));
});

// Platform > Products
Breadcrumbs::for('platform.products', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Sản phẩm'), route('platform.products'));
});

// Platform > Create Product
Breadcrumbs::for('platform.products.create', function (Trail $trail) {
    $trail->parent('platform.products');
    $trail->push(__('Thêm sản phẩm'), route('platform.products.create'));
});

// Platform > Product> {Product Name}
Breadcrumbs::for('platform.products.edit', function (Trail $trail, $product) {
    $trail->parent('platform.products');
    $trail->push($product->title, route('platform.products.edit', $product));
});

// Platform > Branchs Services
Breadcrumbs::for('platform.branchs.services', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Dịch vụ'), route('platform.branchs.services'));
});

// Platform > Create Product
Breadcrumbs::for('platform.branchs.services.create', function (Trail $trail) {
    $trail->parent('platform.branchs.services');
    $trail->push(__('Thêm dịch vụ'), route('platform.branchs.services.create'));
});

// Platform > Product> {Product Name}
Breadcrumbs::for('platform.branchs.services.edit', function (Trail $trail, $branch_service) {
    $trail->parent('platform.branchs.services');
    $trail->push($branch_service->name, route('platform.branchs.services.edit', $branch_service));
});

// Platform > Branchs
Breadcrumbs::for('platform.branchs', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Chi nhánh'), route('platform.branchs'));
});

// Platform > Create Branch
Breadcrumbs::for('platform.branchs.create', function (Trail $trail) {
    $trail->parent('platform.branchs');
    $trail->push(__('Thêm chi nhánh'), route('platform.branchs.create'));
});

// Platform > Branch> {Product Name}
Breadcrumbs::for('platform.branchs.edit', function (Trail $trail, $branch) {
    $trail->parent('platform.branchs');
    $trail->push($branch->address, route('platform.branchs.edit', $branch));
});

// Platform > Consultant
Breadcrumbs::for('platform.consultants', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Hỗ trợ khách hàng'), route('platform.consultants'));
});

// Platform > Create Consultant
Breadcrumbs::for('platform.consultants.create', function (Trail $trail) {
    $trail->parent('platform.consultants');
    $trail->push(__('Thêm'), route('platform.consultants.create'));
});

// Platform > Consultant> {Customer Name}
Breadcrumbs::for('platform.consultants.edit', function (Trail $trail, $consultant) {
    $trail->parent('platform.consultants');
    $trail->push($consultant->customer_name, route('platform.consultants.edit', $consultant));
});

// Platform > Service Categories
Breadcrumbs::for('platform.services_categories', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Danh mục dịch vụ'), route('platform.services_categories'));
});

// Platform > Create Service Categories
Breadcrumbs::for('platform.services_categories.create', function (Trail $trail) {
    $trail->parent('platform.services_categories');
    $trail->push(__('Thêm'), route('platform.services_categories.create'));
});

// Platform > Service Categories> {Service Categories Name}
Breadcrumbs::for('platform.services_categories.edit', function (Trail $trail, $category) {
    $trail->parent('platform.services_categories');
    $trail->push($category->name, route('platform.services_categories.edit', $category));
});

// Platform > Exps
Breadcrumbs::for('platform.exps', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Kinh nghiệm'), route('platform.exps'));
});

// Platform > Create Exps Categories
Breadcrumbs::for('platform.exps.create', function (Trail $trail) {
    $trail->parent('platform.exps');
    $trail->push(__('Thêm'), route('platform.exps.create'));
});

// Platform > Exps> {Exps Title}
Breadcrumbs::for('platform.exps.edit', function (Trail $trail, $category) {
    $trail->parent('platform.exps');
    $trail->push($category->title, route('platform.exps.edit', $category));
});

// Platform > Experience Categories
Breadcrumbs::for('platform.exps_categories', function (Trail $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Danh mục chuyên gia'), route('platform.exps_categories'));
});

// Platform > Create Exps Categories
Breadcrumbs::for('platform.exps_categories.create', function (Trail $trail) {
    $trail->parent('platform.exps_categories');
    $trail->push(__('Thêm'), route('platform.exps_categories.create'));
});

// Platform > Exps Categories> {Exps Categories Name}
Breadcrumbs::for('platform.exps_categories.edit', function (Trail $trail, $category) {
    $trail->parent('platform.exps_categories');
    $trail->push($category->name, route('platform.exps_categories.edit', $category));
});

// Front-end
// ----------------------------------------------------
Breadcrumbs::for('home', function (Trail $trail) {
    $trail->push(__('Trang Chủ'), route('home'));
});

Breadcrumbs::for('contact', function (Trail $trail) {
    $trail->parent('home');
    $trail->push(__('Liên hệ'), route('contact'));
});

Breadcrumbs::for('products', function (Trail $trail) {
    $trail->parent('home');
    $trail->push(__('Sản phẩm'), url('/san-pham'));
});

Breadcrumbs::for('product', function (Trail $trail, $category, Product $product) {
    $trail->parent('products');

    $trail->push($product->title, $product->url->link());
});

Breadcrumbs::for('services', function (Trail $trail) {
    $trail->parent('home');
    $trail->push(__('Dịch vụ khách hàng'), route('services'));
});

Breadcrumbs::for('service', function (Trail $trail, $service) {
    $trail->parent('services');

    $trail->push($service->title, route('service', $service->slug));
});

Breadcrumbs::for('page', function (Trail $trail, Page $page) {
    $trail->parent('home');
    $trail->push($page->title ?? '', $page->url->link);
});

Breadcrumbs::for('platform.funds', function (Trail $trail) {
    $trail->parent('home');
    $trail->push(__('Quản lý quỹ'), url('/funds'));
});

Breadcrumbs::for('platform.funds.create', function (Trail $trail) {
    $trail->parent('platform.funds');
    $trail->push(__('Thêm quỹ liên kết'), route('platform.funds.create'));
});

Breadcrumbs::for('platform.funds.edit', function (Trail $trail, $fundId) {
    $fund = Fund::findOrFail($fundId);
    $trail->parent('platform.funds');
    $trail->push($fund->name, "");
});

Breadcrumbs::for('platform.import_funds', function (Trail $trail, $importFundId) {
    $fund = FundImport::findOrFail($importFundId)->load('fund')->fund;
    $trail->parent('platform.funds');
    $trail->push($fund->name, "");
});
