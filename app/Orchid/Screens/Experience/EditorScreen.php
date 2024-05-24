<?php

namespace App\Orchid\Screens\Experience;

use App\Model\Experience;
use Illuminate\Http\Request;

class EditorScreen
{
    /**
     * //
     *
     * @param  Request  $request
     * @param  Experience  $exp
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request, Experience $exp)
    {
        return view('platform.editor.gutenberg', [
            'content'     => $exp->content,
            'edit_url'    => $exp->url->edit,
            'preview_url' => $exp->url->link,
        ]);
    }

    /**
     * @param  Request  $request
     * @param  Experience  $exp
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function save(Request $request, Experience $exp)
    {
        $content = $request->input('content') ?: '';

        $exp->setContent($content);
        $exp->saveOrFail();

        return response()->json(['message' => 'ok']);
    }
}
