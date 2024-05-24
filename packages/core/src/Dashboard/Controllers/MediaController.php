<?php

namespace Core\Dashboard\Controllers;

use Core\Media\Media;
use Core\Media\MediaLibrary;
use Core\Media\Resources\WPMediaResource;
use Core\Media\Resources\WPMediaCollection;
use Core\Support\MimeTypes;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    /**
     * //
     *
     * @param  Request  $request
     * @return WPMediaCollection
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $request->input('query') ?: [];

        $library = $user->mediaLibrary()
            ->latest()
            ->when((int) ($query['year'] ?? null), function (Builder $builder, $year) {
                $builder->whereYear('media.created_at', $year);
            })
            ->when((int) ($query['monthnum'] ?? null), function (Builder $builder, $month) {
                $builder->whereMonth('media.created_at', $month);
            })
            ->simplePaginate($query['posts_per_page'] ?? 40, ['*'], 'query.paged');

        return new WPMediaCollection($library->getCollection());
    }

    /**
     * Show the media.
     *
     * @param  Media  $media
     * @return WPMediaResource
     */
    public function show(Media $media)
    {
        return new WPMediaResource($media);
    }

    /**
     * Handle upload the media.
     *
     * @param  Request  $request
     * @return WPMediaResource
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('upload', Media::class);

        $this->validate($request, [
            'file'         => 'nullable|mimes:'.implode(',', MimeTypes::getAllowedExtenstions()),
            'async-upload' => 'nullable|mimes:'.implode(',', MimeTypes::getAllowedExtenstions()),
        ]);

        $file = $request->file('file', function () use ($request) {
            return $request->file('async-upload');
        });

        abort_if(!$file, 402);

        $library = MediaLibrary::getForUser($request->user());

        $media = $library
            ->addMedia($file)
            ->toMediaCollection();

        return new WPMediaResource($media);
    }

    /**
     * //
     *
     * @param  Request  $request
     * @param  Media  $media
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Media $media)
    {
        $this->authorize('update', $media);

        $changes = $request->get('changes') ?: [];

        if (isset($changes['title'])) {
            $media->name = $changes['title'];
        }

        foreach (Arr::only($changes, ['alt', 'caption', 'description']) as $key => $value) {
            $media->setCustomProperty($key, clean($value, true) ?: '');
        }

        $media->save();

        return response()->json(['success' => true]);
    }

    /**
     * Perform delete a media.
     *
     * @param  Media  $media
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function destroy(Media $media)
    {
        $this->authorize('upload', $media);

        $media->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Download the media.
     *
     * @param  Media  $media
     * @return \Illuminate\Contracts\Support\Responsable
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function download(Media $media)
    {
        $this->authorize('upload', $media);

        return $media;
    }
}
