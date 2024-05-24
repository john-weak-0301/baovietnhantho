<?php

namespace App\Orchid\Screens\Page;

use App\Model\Page;
use Illuminate\Http\Request;

class EditorScreen
{
    /**
     * //
     *
     * @param  Request  $request
     * @param  Page  $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request, Page $page)
    {
        return view('platform.editor.gutenberg', [
            'content'     => $page->content,
            'edit_url'    => $page->url->edit,
            'preview_url' => $page->url->link,
        ]);
    }

    /**
     * //
     *
     * @param  Request  $request
     * @param  Page  $page
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function save(Request $request, Page $page)
    {
        $content = $request->input('content') ?: '';

        $page->setContent($content);
        $page->saveOrFail();

        return response()->json(['message' => 'ok']);
    }
}
