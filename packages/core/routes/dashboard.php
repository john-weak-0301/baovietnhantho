<?php

use Core\Dashboard\Controllers\AjaxLinksController;
use Core\Dashboard\Controllers\MenuController;
use Core\Dashboard\Controllers\TagsController;
use Core\Media\MediaRepository;
use Illuminate\Support\Facades\Route;
use Core\Dashboard\Screens\NewMediaScreen;
use Core\Dashboard\Screens\MediaLibraryScreen;
use Core\Dashboard\Controllers\MediaController;
use Core\Dashboard\Controllers\StorageController;
use Core\Dashboard\Controllers\AttachmentController;
use Core\Dashboard\Controllers\MediaTemplateController;

Route::bind('medium', function ($id) {
    return app(MediaRepository::class)->findOrFail($id);
});

Route::get('ajax/links', [AjaxLinksController::class, 'links']);
Route::get('ajax/links/search', [AjaxLinksController::class, 'searchLinks']);

// Media screen.
Route::screen('media/new', NewMediaScreen::class, 'dashboard.media.new');
Route::screen('media/{medium}/edit', NewMediaScreen::class, 'dashboard.media.edit');
Route::screen('media', MediaLibraryScreen::class, 'dashboard.media');

// Resource media routes.
Route::resource('resource/media', MediaController::class)
    ->except(['create', 'edit'])
    ->names('dashboard.resource.media');

Route::get('resource/media-template', MediaTemplateController::class);

Route::get('download/media/{medium}', [MediaController::class, 'download'])
    ->name('dashboard.download.media');

// Storage routes.
Route::get('/storage', [StorageController::class, 'index'])
    ->name('platform.systems.media');

Route::group(['as' => 'platform.systems.media.', 'prefix' => '/storage'], function () {
    Route::get('files', [MediaController::class, 'files'])->name('files');
    Route::post('mkdir', [MediaController::class, 'mkdir'])->name('mkdir');
    Route::post('rmdir', [MediaController::class, 'delete'])->name('delete');
    Route::post('rename', [MediaController::class, 'rename'])->name('rename');
    Route::post('upload', [MediaController::class, 'upload'])->name('upload');
    Route::post('remove', [MediaController::class, 'remove'])->name('remove');
    Route::post('move', [MediaController::class, 'move'])->name('move');
    Route::post('crop', [MediaController::class, 'crop'])->name('crop');
});

// Tags
Route::get('tags/{tags?}', TagsController::class)
    ->name('systems.tag.search');

Route::resource('menu', MenuController::class, [
    'only'  => [
        'index', 'show', 'update', 'store', 'destroy',
    ],
    'names' => [
        'index'   => 'systems.menu.index',
        'show'    => 'systems.menu.show',
        'update'  => 'systems.menu.update',
        'store'   => 'systems.menu.store',
        'destroy' => 'systems.menu.destroy',
    ],
]);

// Overwrite orchid.
App::booted(function () {
    Route::post('dashboard/systems/files', [AttachmentController::class, 'upload'])
        ->middleware(config('platform.middleware.private'))
        ->name('platform.systems.files.upload');
});
