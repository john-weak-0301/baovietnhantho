<?php

namespace App\Model\Observers;

use App\Model\News;
use Illuminate\Support\Facades\Auth;

class NewsObserver
{
    /**
     * Handle the news "creating" event.
     *
     * @param  News  $news
     * @return void
     */
    public function creating(News $news): void
    {
        $news->content = $news->content ?? '';

        if (!$news->author_id && $author = Auth::id()) {
            $news->author_id = $author;
        }
    }

    public function deleting(News $news): void
    {
        $news->categories()->detach();
    }
}
