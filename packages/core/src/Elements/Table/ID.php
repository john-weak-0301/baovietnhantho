<?php

namespace Core\Elements\Table;

use Illuminate\Support\HtmlString;

class ID extends Column
{
    /**
     * Create a new column.
     *
     * @param  string  $column
     * @param  string  $name
     * @return void
     */
    public function __construct(string $column = 'id', string $name = 'ID')
    {
        parent::__construct($name, $column);
    }

    /**
     * Setup the column.
     */
    public function setup(): void
    {
        $this->width('45px');

        $this->displayUsing(function ($resource, $value) {
            return new HtmlString(sprintf(
                '<input type="checkbox" name="resources[%1$s]" value="%1$s"/>',
                $value ?: optional($resource)->getKey()
            ));
        });
    }
}
