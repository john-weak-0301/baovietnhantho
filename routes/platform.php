<?php

use App\Dashboard\Screens\BlockEditorScreen;
use App\Dashboard\Screens\BlockListScreen;
use App\Orchid\Screens\OptionsScreen;
use App\Orchid\Screens\Page;
use App\Orchid\Screens\News;
use App\Orchid\Screens\Service;
use App\Orchid\Screens\Contact;
use App\Orchid\Screens\Category;
use App\Orchid\Screens\Counselor;
use App\Orchid\Screens\ProductCategory;
use App\Orchid\Screens\Product;
use App\Orchid\Screens\BranchService;
use App\Orchid\Screens\Branch;
use App\Orchid\Screens\Menu;
use App\Orchid\Screens\Fund;
use App\Orchid\Screens\FundCost;
use App\Orchid\Screens\AdditionProduct;
use App\Orchid\Screens\Consultant;
use App\Orchid\Screens\Personality;
use App\Orchid\Screens\ServiceCategory;
use App\Orchid\Screens\Experience;
use App\Orchid\Screens\ExperienceCategory;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Popup;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Controllers\MediaController;
use App\Orchid\Controllers\BlockController;
use App\Orchid\Controllers\OEmbedController;
use App\Dashboard\Controllers\PrepareImportController;
use App\Dashboard\Controllers\ImportCounselorsController;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)->name('platform.main');
Route::get('logs', LogViewerController::class.'@index');

// Editor
Route::get('/gutenberg/oembed', OEmbedController::class);
Route::get('/gutenberg/media/{id}', [MediaController::class, 'show'])->where('id', '[\d]+');
Route::post('/gutenberg/media', [MediaController::class, 'upload']);

// Blocks screen.
Route::get('blocks/{blockId}/editor', [BlockEditorScreen::class, 'show'])->name('dashboard.blocks.editor');
Route::post('blocks/{blockId}/editor', [BlockEditorScreen::class, 'save']);
Route::screen('blocks', BlockListScreen::class, 'dashboard.blocks');
Route::apiResource('gutenberg/blocks', BlockController::class);

// News
Route::get('news/{newsId}/editor', News\EditorScreen::class)->name('platform.news.editor');
Route::post('news/{newsId}/editor', [News\EditorScreen::class, 'save']);
Route::screen('news/{newsId}/edit', News\EditScreen::class)->name('platform.news.edit');
Route::screen('news/create', News\EditScreen::class)->name('platform.news.create');
Route::screen('news', News\ListScreen::class)->name('platform.news');

// Pages
Route::screen('home', Page\HomeScreen::class)->name('platform.home');
Route::screen('home/editor', Page\HomeScreen::class)->name('platform.home.editor');

Route::get('pages/{pageId}/editor', Page\EditorScreen::class)->name('platform.pages.editor');
Route::post('pages/{pageId}/editor', [Page\EditorScreen::class, 'save']);
Route::screen('pages/{pageId}/edit', Page\EditScreen::class)->name('platform.pages.edit');
Route::screen('pages/create', Page\EditScreen::class)->name('platform.pages.create');
Route::screen('pages', Page\ListScreen::class)->name('platform.pages');

// Users...
Route::screen('users/{users}/edit', UserEditScreen::class)->name('platform.systems.users.edit');
Route::screen('users', UserListScreen::class)->name('platform.systems.users');

// Options
Route::screen('options', OptionsScreen::class)->name('platform.systems.options');

// Roles...
Route::screen('roles/{roles}/edit', RoleEditScreen::class)->name('platform.systems.roles.edit');
Route::screen('roles/create', RoleEditScreen::class)->name('platform.systems.roles.create');
Route::screen('roles', RoleListScreen::class)->name('platform.systems.roles');

// Sevices
Route::get('services/{serviceId}/editor', Service\EditorScreen::class)->name('platform.services.editor');
Route::post('services/{serviceId}/editor', [Service\EditorScreen::class, 'save']);
Route::screen('services/{serviceId}/edit', Service\EditScreen::class)->name('platform.services.edit');
Route::screen('services/create', Service\EditScreen::class)->name('platform.services.create');
Route::screen('services', Service\ListScreen::class)->name('platform.services');

// Categories
Route::screen('categories/{categoryId}/edit', Category\EditScreen::class)->name('platform.categories.edit');
Route::screen('categories/create', Category\EditScreen::class)->name('platform.categories.create');
Route::screen('categories', Category\ListScreen::class)->name('platform.categories');

// Counselors
Route::screen('counselors/{counselorId}/edit', Counselor\EditScreen::class)->name('platform.counselors.edit');
Route::screen('counselors/create', Counselor\EditScreen::class)->name('platform.counselors.create');
Route::screen('counselors', Counselor\ListScreen::class)->name('platform.counselors');
//Personalities
Route::screen('personalities/{personalityId}/edit', Personality\EditScreen::class)->name('platform.personalities.edit');
Route::screen('personalities/create', Personality\EditScreen::class)->name('platform.personalities.create');
Route::screen('personalities', Personality\ListScreen::class)->name('platform.personalities');

// Contacts
Route::screen('contacts/{contactId}', Contact\ShowScreen::class)->name('platform.contacts.show');
Route::screen('contacts', Contact\ListScreen::class)->name('platform.contacts');

//Products-Categories
Route::screen('product/categories/{productCategoryId}/edit',
    ProductCategory\EditScreen::class)->name('platform.products.categories.edit');
Route::screen('product/categories/create',
    ProductCategory\EditScreen::class)->name('platform.products.categories.create');
Route::screen('product/categories', ProductCategory\ListScreen::class)->name('platform.products.categories');

//Products
Route::get('products/{productId}/editor', Product\EditorScreen::class)->name('platform.products.editor');
Route::post('products/{productId}/editor', [Product\EditorScreen::class, 'save']);
Route::screen('products/{productId}/edit', Product\EditScreen::class)->name('platform.products.edit');
Route::screen('products/create', Product\EditScreen::class)->name('platform.products.create');
Route::screen('products', Product\ListScreen::class)->name('platform.products');
Route::screen('addition-products', AdditionProduct\ListScreen::class)->name('platform.addition_products');

// Branchs Services
Route::screen('branch/services/{branchServiceId}/edit',
    BranchService\EditScreen::class)->name('platform.branchs.services.edit');
Route::screen('branch/services/create',
    BranchService\EditScreen::class)->name('platform.branchs.services.create');
Route::screen('branch/services', BranchService\ListScreen::class)->name('platform.branchs.services');

// Branchs
Route::screen('branchs/{branchId}/edit', Branch\EditScreen::class)->name('platform.branchs.edit');
Route::screen('branchs/create', Branch\EditScreen::class)->name('platform.branchs.create');
Route::screen('branchs', Branch\ListScreen::class)->name('platform.branchs');

//Consultants
Route::screen('consultants/{consultantId}/edit', Consultant\EditScreen::class)
    ->name('platform.consultants.edit');
Route::screen('consultants/create', Consultant\EditScreen::class)
    ->name('platform.consultants.create');
Route::screen('consultants', Consultant\ListScreen::class)
    ->name('platform.consultants');

// Service Categories
Route::screen('services_categories/{serviceCategoryId}/edit', ServiceCategory\EditScreen::class)
    ->name('platform.services_categories.edit');
Route::screen('services_categories/create', ServiceCategory\EditScreen::class)
    ->name('platform.services_categories.create');
Route::screen('services_categories', ServiceCategory\ListScreen::class)
    ->name('platform.services_categories');

// Exps
Route::get('exps/{expId}/editor', Experience\EditorScreen::class)->name('platform.exps.editor');
Route::post('exps/{expId}/editor', [Experience\EditorScreen::class, 'save']);
Route::screen('exps/{expId}/edit', Experience\EditScreen::class)
    ->name('platform.exps.edit');
Route::screen('exps/create', Experience\EditScreen::class)
    ->name('platform.exps.create');
Route::screen('exps', Experience\ListScreen::class)
    ->name('platform.exps');

// Experience Categories
Route::screen('exps_categories/{expCategoryId}/edit', ExperienceCategory\EditScreen::class)
    ->name('platform.exps_categories.edit');
Route::screen('exps_categories/create', ExperienceCategory\EditScreen::class)
    ->name('platform.exps_categories.create');
Route::screen('exps_categories', ExperienceCategory\ListScreen::class)
    ->name('platform.exps_categories');

Route::post('import/prepare', PrepareImportController::class);
Route::post('import/counselors', ImportCounselorsController::class);


Route::get('menu/{menuId}/editor', Menu\EditorScreen::class)->name('platform.menu.editor');
Route::post('menu/{menuId}/editor', [Menu\EditorScreen::class, 'save']);

Route::get('popups/{popupId}/editor', Popup\EditorScreen::class)->name('platform.popups.editor');
Route::post('popups/{popupId}/editor', [Popup\EditorScreen::class, 'save']);
Route::screen('popups/{popupId}/edit', Popup\EditScreen::class)->name('platform.popups.edit');
Route::screen('popups/create', Popup\EditScreen::class)->name('platform.popups.create');
Route::screen('popups', Popup\ListScreen::class)->name('platform.popups');

// Funds
Route::post('funds/{id}/edit/import', [Fund\EditScreen::class, 'import'])->name('platform.funds.edit');
Route::screen('funds/{id}/edit', Fund\EditScreen::class)->name('platform.funds.edit');
Route::screen('funds/create', Fund\EditScreen::class)->name('platform.funds.create');
Route::screen('funds', Fund\ListScreen::class)->name('platform.funds');

// Import Funds
Route::get('import_funds/{id}/update', [FundCost\ListScreen::class, 'update'])->name('platform.import_funds.update');
Route::get('import_funds/{id}/delete', [FundCost\ListScreen::class, 'delete'])->name('platform.import_funds.delete');
Route::screen('import_funds/{id}/edit', FundCost\ListScreen::class)->name('platform.import_funds');
