<?php

namespace App\Dashboard\Screens;

use App\Model\Block;
use Illuminate\Http\Request;

class BlockEditorScreen
{
    /**
     * @param  Request  $request
     * @param  Block  $block
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Block $block)
    {
        return view('platform.editor.gutenberg', [
            'content'     => $block->raw_content,
            'edit_url'    => route('dashboard.blocks'),
            'preview_url' => '',
        ]);
    }

    /**
     * @param  Request  $request
     * @param  Block  $block
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request, Block $block)
    {
        $content = $request->input('content') ?: '';

        $block->setContent($content);
        $block->saveOrFail();

        return response()->json(['message' => 'ok']);
    }
}
