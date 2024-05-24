<?php

namespace Core\Media\Resources;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Core\Media\Media
 */
class WPMediaResource extends JsonResource
{
    /**
     * The additional data that should be added to the top-level resource array.
     *
     * @var array
     */
    public $with = [
        'success' => true,
    ];

    /**
     * //
     *
     * @return $this
     */
    public function onlyResourceArray()
    {
        static::withoutWrapping();

        $this->with = [];

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $subtype = '';
        if (strpos($this->mime_type, '/') !== false) {
            $subtype = explode('/', $this->mime_type)[1] ?? '';
        }

        $response = [
            'id'                    => $this->getKey(),
            'title'                 => $this->name,
            'filename'              => $this->file_name,
            'url'                   => $this->getFullUrl(),
            'link'                  => null, // No public link.
            'author'                => $this->user_id ?? 0,
            'alt'                   => $this->getCustomProperty('alt', ''),
            'caption'               => $this->getCustomProperty('caption', ''),
            'description'           => $this->getCustomProperty('description', ''),
            'name'                  => '', // No post-slug
            'status'                => 'inherit',
            'uploadedTo'            => 0,
            'date'                  => optional($this->created_at)->timestamp ?: null,
            'modified'              => optional($this->updated_at)->timestamp ?: null,
            'menuOrder'             => $this->order_column,
            'mime'                  => $this->mime_type,
            'type'                  => $this->getTypeFromExtension(),
            'subtype'               => $subtype,
            'icon'                  => '',
            'dateFormatted'         => $this->created_at ? $this->created_at->diffForHumans() : '',
            'filesizeHumanReadable' => $this->humanReadableSize,
            'filesizeInBytes'       => $this->size,
            'editLink'              => false,
            'nonces'                => [
                'update' => false,
                'delete' => false,
                'edit'   => false,
            ],
        ];

        $token = csrf_token();

        if (Gate::allows('edit', $this->resource)) {
            $response['editLink']         = route('dashboard.media.edit', $this->id);
            $response['nonces']['edit']   = $token;
            $response['nonces']['update'] = $token;
        }

        if (Gate::allows('delete', $this->resource)) {
            $response['nonces']['delete'] = $token;
        }

        $conversions = $this->getGeneratedConversions()->keys();

        if ($response['type'] === 'image' || $conversions) {
            $sizes['full'] = ['url' => $response['url']];

            foreach ($conversions as $conversion) {
                $sizes[$conversion] = ['url' => $this->getFullUrl($conversion)];
            }

            $response['sizes'] = $sizes;
        }

        return $response;
    }
}
