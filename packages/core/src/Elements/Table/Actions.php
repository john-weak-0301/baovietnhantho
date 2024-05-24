<?php

namespace Core\Elements\Table;

use Core\Elements\Link;
use Orchid\Screen\Repository;
use Illuminate\Support\HtmlString;

class Actions extends Column
{
    /**
     * The action links.
     *
     * @var callable|Link[]
     */
    public $actions;

    /**
     * Create a new column.
     *
     * @param  string  $name
     * @param  callable|Link[]|null  $actions
     * @return void
     */
    public function __construct(string $name, $actions = null)
    {
        parent::__construct('__actions__', $name);

        $this->actions = $actions;
    }

    /**
     * Sets the actions.
     *
     * @param  callable|Link[]  $actions
     * @return $this
     */
    public function actions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Setup the column.
     */
    public function setup(): void
    {
        $this->align(static::ALIGN_RIGHT);

        $this->displayUsing(function ($data) {
            $actions = $this->actions;

            if (is_callable($actions)) {
                $actions = $actions($data);
            }

            if (is_array($actions) && count($actions) > 0) {
                $this->preapreActions($actions);

                $data = new Repository(['data' => $data]);

                return new HtmlString(
                    view('core::layouts.table.column-actions', compact('data', 'actions'))->render()
                );
            }

            return '';
        });
    }

    /**
     * Prepare actions.
     *
     * @param  array  $actions
     */
    protected function preapreActions(array $actions)
    {
        foreach ($actions as $action) {
            if ($action instanceof Link) {
                $action->addClass('dropdown-item');
            }
        }
    }
}
