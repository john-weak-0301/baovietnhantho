<?php

namespace App\Orchid\Layout;

use Orchid\Screen\Builder;
use Orchid\Screen\Repository;
use Orchid\Screen\Layouts\Base;
use Orchid\Screen\Contracts\FieldContract;

abstract class TwoColumnsLayout extends Base
{
    /**
     * @var Repository
     */
    public $query;

    /**
     * @var string
     */
    public $template = 'platform.container.layouts.two-columns';

    /**
     * Define the field in the main area.
     *
     * @return FieldContract[]
     */
    abstract public function mainFields(): array;

    /**
     * Define the field in the sidebar area.
     *
     * @return FieldContract[]
     */
    abstract public function sidebarFields(): array;

    /**
     * Build the layout.
     *
     * @param  Repository  $query
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Throwable
     */
    public function build(Repository $query)
    {
        $this->query = $query;

        $mainForm = new Builder($this->mainFields(), $this->query);

        $sidebarForm = new Builder($this->sidebarFields(), $this->query);

        return view($this->template, [
            'mainForm'    => $mainForm->generateForm(),
            'sidebarForm' => $sidebarForm->generateForm(),
        ]);
    }
}
