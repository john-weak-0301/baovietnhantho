<?php

namespace Core\Dashboard\Controllers;

use Core\Dashboard\Models\Tag;

class TagsController extends Controller
{
    /**
     * //
     *
     * @param  string  $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($tag)
    {
        $tags = Tag::latest('count')
            ->where('name', 'like', '%'.$tag.'%')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item['name'],
                    'text'  => $item['name'],
                    'count' => $item['count'],
                ];
            })
            ->toArray();

        return response()->json($tags);
    }
}
