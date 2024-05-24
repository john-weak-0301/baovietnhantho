<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeHoachController
{
    public function __invoke(Request $request, $name = null)
    {
        // Redirect from "/lap-ke-hoach?plan=dau-tu" to "/lap-ke-hoach/dau-tu".
        if ($name === null && $request->has('plan')) {
            return redirect('/lap-ke-hoach/'.$request->get('plan'));
        }

        $objectives = config('press.objectives');

        abort_if($name && !array_key_exists($name, $objectives), 404);

        $title = 'Lập kế hoạch tài chính';

        if ($name) {
            $title = $objectives[$name]['name'] ?? $title;
        }

        return view('lap-ke-hoach', [
            'title' => $title,
            'data'  => [
                'current'    => $name,
                'objectives' => $objectives,
            ],
        ]);
    }
}
