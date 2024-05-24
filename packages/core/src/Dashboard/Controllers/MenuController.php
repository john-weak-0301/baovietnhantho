<?php

namespace Core\Dashboard\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Core\Dashboard\Models\Menu;

class MenuController extends Controller
{
    /**
     * @var string
     */
    public $menu;

    /**
     * MenuController constructor.
     */
    public function __construct()
    {
        // $this->authorize('platform.systems.menu');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function index()
    {
        $availableMenus = collect(config('press.menu'));

        if ($availableMenus->count() > 0) {
            return redirect()->route('systems.menu.show', $availableMenus->keys()->first());
        }

        abort(404);
    }

    /**
     * @param  string  $name
     * @param  Request  $request
     *
     * @return View
     */
    public function show(string $name, Request $request)
    {
        $availableMenus = config('press.menu');

        $menu = Menu::query()
            ->where('parent', 0)
            ->where('type', $name)
            ->orderBy('sort')
            ->with('children')
            ->get();

        return view('core::systems.menu', [
            'name'           => $name,
            'menu'           => $menu,
            'availableMenus' => $availableMenus,
        ]);
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $menu = Menu::create(array_merge($request->input('params.data'), [
            'type'   => $request->input('params.menu'),
            'parent' => 0,
        ]));

        return response()->json([
            'type' => 'success',
            'id'   => $menu->id,
        ]);
    }

    /**
     * @param  string  $menu
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $menu)
    {
        $this->menu = $menu;

        $this->createMenuElement($request->get('data'));

        return response()->json([
            'type' => 'success',
        ]);
    }

    /**
     * @param  array  $items
     * @param  int  $parent
     */
    private function createMenuElement(array $items, $parent = 0)
    {
        foreach ($items as $item) {
            Menu::firstOrNew([
                'id'   => $item['id'],
            ])->fill(array_merge($item, [
                'type'   => $this->menu,
                'parent' => $parent,
            ]))->save();

            if (array_key_exists('children', $item)) {
                $this->createMenuElement($item['children'], $item['id']);
            }
        }
    }

    /**
     * @param  Menu  $menu
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     */
    public function destroy(Menu $menu)
    {
        Menu::where('parent', $menu->id)->delete();

        $menu->delete();

        return response()->json([
            'type' => 'success',
        ]);
    }
}
