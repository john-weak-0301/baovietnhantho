<?php

namespace App\Orchid\Controllers;

use Throwable;
use Embed\Embed;
use Embed\Adapters\Adapter;
use Illuminate\Http\Request;

class OEmbedController
{
    public function __invoke(Request $request)
    {
        try {
            $json = $this->transform(
                Embed::create($request->input('url'))
            );
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        if (empty($json['html'])) {
            return response()->json(['message' => 'not_found'], 400);
        }

        return response()->json($json);
    }

    /**
     * Transforms the Embed to a format that Gutenberg can handle.
     *
     * @param  Adapter  $embed
     * @return array
     */
    public function transform(Adapter $embed): array
    {
        return [
            'url'           => $embed->url,
            'author_name'   => $embed->authorName,
            'author_url'    => $embed->authorUrl,
            'html'          => $embed->code,
            'width'         => $embed->width,
            'height'        => $embed->height,
            'type'          => $embed->type,
            'provider_name' => $embed->providerName,
            'provider_url'  => $embed->providerUrl,
        ];
    }
}
