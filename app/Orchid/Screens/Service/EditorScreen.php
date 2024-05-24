<?php

namespace App\Orchid\Screens\Service;

use App\Model\Service;
use Illuminate\Http\Request;

class EditorScreen
{
    /**
     * @param  Request  $request
     * @param  Service  $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request, Service $service)
    {
        return view('platform.editor.gutenberg', [
            'content' => $service->content,
            'edit_url' => route('platform.services.edit', [$service]),
            'preview_url' => route('service', [$service->slug]),
        ]);
    }

    /**
     * @param  Request  $request
     * @param  Service  $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function save(Request $request, Service $service)
    {
        $content = $request->input('content') ?: '';

        $service->setContent($content);
        $service->saveOrFail();

        return response()->json(['message' => 'ok']);
    }
}
