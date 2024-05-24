<?php

namespace Core\Dashboard\Controllers;

use Core\Media\Media;
use Core\Media\MediaLibrary;
use Core\Media\Resources\WPMediaResource;
use Spatie\MediaLibrary\FileAdder\FileAdder;
use Illuminate\Http\Request;

/**
 * Overwrite the orchid routes.
 */
class AttachmentController extends Controller
{
    /**
     * //
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function upload(Request $request)
    {
        $this->authorize('upload', Media::class);

        $library = MediaLibrary::getForUser($request->user());

        $attachments = collect($library->addAllMediaFromRequest())
            ->map(function (FileAdder $fileAdder) {
                return $fileAdder->toMediaCollection();
            })->values();

        if ($attachments->count() > 0) {
            return (new WPMediaResource($attachments[0]))->onlyResourceArray();
        }
    }

    public function sort(Request $request)
    {
        return response(200);
    }

    public function destroy(Request $request, int $id)
    {
        $media = MediaLibrary::getForUser($request->user())
            ->media()
            ->whereKey($id)
            ->firstOrFail();

        $media->delete();

        return response(200);
    }

    public function update(Request $request, int $id)
    {
        $media = MediaLibrary::getForUser($request->user())
            ->media()
            ->whereKey($id)
            ->firstOrFail();

        foreach ($request->only(['alt', 'caption', 'description']) as $key => $value) {
            $media->setCustomProperty($key, clean($value, true) ?: '');
        }

        $media->save();

        return (new WPMediaResource($media))->onlyResourceArray();
    }
}
