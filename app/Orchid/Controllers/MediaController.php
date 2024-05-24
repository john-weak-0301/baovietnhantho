<?php

namespace App\Orchid\Controllers;

use Core\Media\Media;
use Core\Media\MediaLibrary;
use Core\Support\MimeTypes;
use Illuminate\Http\Request;

class MediaController
{
    public function show($media)
    {
        $media = Media::findOrFail($media);

        return $this->response($media);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file'         => 'nullable|mimes:'.implode(',', MimeTypes::getAllowedExtenstions()),
            'async-upload' => 'nullable|mimes:'.implode(',', MimeTypes::getAllowedExtenstions()),
        ]);

        $file = $request->file('file', function () use ($request) {
            return $request->file('async-upload');
        });

        $library = MediaLibrary::getForUser($request->user());

        return $this->response(
            $library->addMedia($file)->toMediaCollection()
        );
    }

    /**
     * @param  Media  $media
     * @return array
     */
    protected function response($media)
    {
        $sizes       = [];
        $conversions = $media->getGeneratedConversions()->keys();

        if ($media->getTypeFromMime() === 'image' || $conversions) {
            $sizes['full'] = [
                'url'        => $media->getFullUrl(),
                'source_url' => $media->getFullUrl(),
                'file'       => basename($media->getUrl()),
            ];

            foreach ($conversions as $conversion) {
                $sizes[$conversion] = [
                    'url'        => $media->getFullUrl($conversion),
                    'source_url' => $media->getFullUrl($conversion),
                    'file'       => basename($media->getUrl($conversion)),
                ];
            }
        }

        return [
            'id'            => $media->id,
            'guid'          => [
                'rendered' => $media->name,
            ],
            'link'          => $media->getFullUrl(),
            'source_url'    => $media->getFullUrl(),
            'modified'      => $media->updated_at,
            'modified_gmt'  => $media->updated_at,
            'date'          => $media->created_at,
            'date_gmt'      => $media->created_at,
            'media_type'    => $media->getTypeFromMime(),
            'mime_type'     => $media->mime_type,
            'status'        => 'publish',
            'type'          => 'attachment',
            'alt_text'      => '',
            'caption'       => [],
            'description'   => [],
            '_links'        => [],
            'title'         => [
                'rendered' => $media->name,
            ],
            'media_details' => [
                'file'  => $media->file_name,
                'sizes' => $sizes,
            ],
        ];
    }
}
