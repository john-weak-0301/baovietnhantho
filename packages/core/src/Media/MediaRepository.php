<?php

namespace Core\Media;

use Core\Database\Repository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class MediaRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Media::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'file_name',
    ];

    /**
     * Find a media by ID.
     *
     * @param  int  $id
     * @return Media
     */
    public function findOrFail($id): Media
    {
        return Media::whereKey($id)
            ->where('model_type', MediaLibrary::class)
            ->firstOrFail();
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    protected function applyMainQuery(Request $request, $query): Builder
    {
        return $query
            ->leftJoin('media_libraries', 'media.model_id', 'media_libraries.id')
            ->where('media.model_type', MediaLibrary::class)
            ->select(['media.*', 'media_libraries.user_id']);
    }
}
