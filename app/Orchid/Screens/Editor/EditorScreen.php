<?php

namespace App\Orchid\Screens\Editor;

class EditorScreen
{
    /**
     * Constructor.
     */
    public function __invoke()
    {
        return view('platform.editor.gutenberg');
    }
}
