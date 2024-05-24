<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AdditionProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductCompareController;
use App\Http\Controllers\KeHoachController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\FundCostController;
use App\Http\Controllers\FundController;
use App\Dashboard\Controllers\SubdivisionController;

if (!defined('ROUTE_PATTERN_SLUG')) {
    define('ROUTE_PATTERN_SLUG', '[A-z0-9-]+');
}

Route::pattern('slug', ROUTE_PATTERN_SLUG);
Route::pattern('tagName', ROUTE_PATTERN_SLUG);
Route::pattern('pageSlug', ROUTE_PATTERN_SLUG);
Route::pattern('categorySlug', ROUTE_PATTERN_SLUG);
Route::pattern('productCategoryName', ROUTE_PATTERN_SLUG);
Route::pattern('productName', ROUTE_PATTERN_SLUG);

/* @deprecated */
Route::redirect('/chuyen-vien', '/tu-van', 301);

Route::get('/tim-kiem', SearchController::class)->name('search');
Route::get('/json/branches.json', [BranchController::class, 'json']);
Route::get('/json/services.json', [ServiceController::class, 'json']);
Route::get('data/subdivision.json', [SubdivisionController::class, 'provinces']);
Route::get('data/subdivision/{province}.json', [SubdivisionController::class, 'districts']);
Route::get('data/subdivision/{province}/{district}.json', [SubdivisionController::class, 'wards']);
Route::get('/search-fund-costs', [FundCostController::class, 'search']);

Route::middleware('cache')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/quy-lien-ket-don-vi', [FundController::class, 'show'])->name('funds');
    Route::get('/tag/{tagName?}', TagController::class)->name('tags');

    Route::get('/tin-tuc/danh-muc/{categorySlug}', [NewsController::class, 'category'])->name('news.category');
    Route::get('/tin-tuc/{slug}', [NewsController::class, 'show'])->name('news');
    Route::get('/tin-tuc', [NewsController::class, 'home'])->name('news.home');

    Route::get('/san-pham/so-sanh', ProductCompareController::class);
    Route::get('/san-pham/danh-muc/{productCategoryName}', ProductCategoryController::class)->name('product.category');
    Route::get('/san-pham/bo-tro/{productName}', AdditionProductController::class)->name('product.addition');
    Route::get('/san-pham/{productCategorySlug}/{productName?}', ProductController::class)->name('product');

    Route::get('/dich-vu-khach-hang', [ServiceController::class, 'index'])->name('services');
    Route::get('/dich-vu-khach-hang/{slug}', [ServiceController::class, 'show'])->name('service');

    Route::view('/thank-you', 'thank-you')->name('thank-you');
    Route::get('/lap-ke-hoach/{name?}', KeHoachController::class);
    Route::get('/tu-van', ToolsController::class)->name('tool');
    Route::get('/chi-nhanh', [BranchController::class, 'index'])->name('branches');

    Route::get('/lien-he', [ContactController::class, 'contact'])->name('contact');
    Route::post('/lien-he', [ContactController::class, 'action'])->name('contact.submit');

    Route::get('/goc-chuyen-gia', [ExperienceController::class, 'home'])->name('exps.home');
    Route::get('/goc-chuyen-gia/danh-muc/{slug}', [ExperienceController::class, 'category'])->name('exps.category');
    Route::get('/goc-chuyen-gia/{slug}', [ExperienceController::class, 'show'])->name('exp');

    Route::get('/{pageSlug}', [PageController::class, 'show'])->name('page');
});
