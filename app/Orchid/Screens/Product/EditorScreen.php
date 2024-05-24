<?php

namespace App\Orchid\Screens\Product;

use App\Model\Product;
use Illuminate\Http\Request;

class EditorScreen
{
    /**
     * @param  Request  $request
     * @param  Product  $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request, Product $product)
    {
        return view('platform.editor.gutenberg', [
            'content'     => $product->content,
            'edit_url'    => $product->url->edit(),
            'preview_url' => $product->url->link(),
        ]);
    }

    /**
     * @param  Request  $request
     * @param  Product  $product
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function save(Request $request, Product $product)
    {
        $content = $request->input('content') ?: '';

        $product->setContent($content);
        $product->saveOrFail();

        return response()->json(['message' => 'ok']);
    }
}
