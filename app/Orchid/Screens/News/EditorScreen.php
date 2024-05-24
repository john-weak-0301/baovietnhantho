<?php

namespace App\Orchid\Screens\News;

use App\Model\News;
use Illuminate\Http\Request;

class EditorScreen
{
    /**
     * //
     *
     * @param  Request  $request
     * @param  News  $news
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request, News $news)
    {
        return view('platform.editor.gutenberg', [
            'content'     => $news->content,
            'edit_url'    => $news->url->edit(),
            'preview_url' => $news->url->link,
        ]);
    }

    /**
     * @param  Request  $request
     * @param  News  $news
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function save(Request $request, News $news)
    {
        $content = $request->input('content') ?: '';

        $news->setContent($content);
        $news->saveOrFail();

        return response()->json(['message' => 'ok']);
    }
}
