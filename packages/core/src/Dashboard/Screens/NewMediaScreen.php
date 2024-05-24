<?php

namespace Core\Dashboard\Screens;

use Core\Media\Helper;
use Core\Media\MediaLibrary;
use Core\Screens\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;

class NewMediaScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Media';

    /**
     * {@inheritdoc}
     */
    public function query(Request $request): array
    {
        return [
            'max_upload_size' => Helper::sizeFormat(Helper::getMaxUploadSize()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            Layout::view('core::media.upload'),
        ];
    }

    public function upload(Request $request)
    {
        $user = $request->user();

        $library = MediaLibrary::getForUser($user);

        foreach ($request->allFiles() as $file) {
            $library->addMedia($file)->toMediaCollection();
        }

        return redirect()->back();
    }
}
