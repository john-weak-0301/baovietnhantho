<?php

namespace App\Dashboard\Layouts;

use Orchid\Screen\Layouts\Base;
use Orchid\Screen\Layouts\Wrapper;

class EditTagsLayout extends Wrapper
{
    /**
     * Constructor.
     *
     * @param  Base|string  $form
     * @param  Base|string  $table
     */
    public function __construct($form, $table)
    {
        parent::__construct('platform.layouts.edit-tags', compact('form', 'table'));
    }
}
