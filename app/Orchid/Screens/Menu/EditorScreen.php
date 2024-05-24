<?php

namespace App\Orchid\Screens\Menu;

use App\Model\News;
use Illuminate\Http\Request;
use Core\Dashboard\Models\Menu as MenuModel;

class EditorScreen
{
    /**
     * @param Request $request
     * @param int $menuId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request, $menuId)
    {
        $menu = MenuModel::query()->findOrFail($menuId);

        if ($menu->type !== 'main') {
            abort(403);
        }

        return view('platform.editor.gutenberg', [
            'content' => $menu->content ?? '',
            'edit_url' => '',
            'preview_url' => '',
        ]);
    }

    /**
     * @param Request $request
     * @param int $menuId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function save(Request $request, $menuId)
    {
        $menu = MenuModel::query()->findOrFail($menuId);

        if ($menu->type !== 'main') {
            abort(403);
        }

        $content = $request->input('content') ?: '';

        $menu->content = $content;
        $menu->saveOrFail();

        return response()->json(['message' => 'ok']);
    }
}
