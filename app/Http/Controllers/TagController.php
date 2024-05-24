<?php

namespace App\Http\Controllers;

use Core\Dashboard\Models\Tag;

class TagController
{
    public function __invoke($tag)
    {
        $tag = Tag::findBySlugOrFail($tag);

        // Load all tagged models.
        $tag->load('tagged.taggable');

        $tagged = $tag->tagged->map(function ($tagged) {
            return $tagged->taggable;
        })->filter()->values();

        return view('tag', [
            'tag'    => $tag,
            'tagged' => $tagged,
            'title'  => sprintf('Từ khóa: "%s"', $tag->name),
        ]);
    }
}
