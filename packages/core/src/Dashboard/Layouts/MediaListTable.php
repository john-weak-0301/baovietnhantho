<?php

namespace Core\Dashboard\Layouts;

use Core\Media\Media;
use Core\Elements\Link;
use Core\Elements\Table\ID;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\Actions;
use Core\Elements\Table\LinkBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MediaListTable extends Table
{
    /**
     * Prepare the query data.
     *
     * @param  Request  $request
     * @param  Builder  $query
     */
    public function prepare(Request $request, Builder $query)
    {
        $query->with('user');

        if ($request->filled('author')) {
            $query->where('user_id', (int) $request->get('author'));
        }
    }

    /**
     * Define the table columns.
     *
     * @return Column[]
     */
    public function columns(): array
    {
        return [
            ID::make(),

            Column::make('name', __('Media'))
                ->displayUsing(function (Media $media) {
                    return view('core::media.column-name', compact('media'));
                }),

            Column::make('user', __('Đăng bởi'))
                ->displayUsing(function (Media $media) {
                    return optional($media->user)->username ?: optional($media->user)->name;
                })
                ->linkTo(function (LinkBuilder $link, Media $media) {
                    if ($media->user) {
                        $link->link(add_query_arg('author', $media->user->id, url()->current()));
                    }
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->width('120px')
                ->displayUsing(function (Media $media, $created_at) {
                    return optional($created_at)->diffForHumans();
                }),

            Column::make('link', __('Link'))
                ->width('350px')
                ->displayUsing(function (Media $media) {
                    return view('core::media.column-link', compact('media'));
                }),

            Actions::make('#', function (Media $media) {
                return [
                    Link::to(route('dashboard.download.media', $media->id), __('Tải về'))
                        ->icon('icon-cloud-download')
                        ->openNewTab(),
                ];
            }),
        ];
    }
}
