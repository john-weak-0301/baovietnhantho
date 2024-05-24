<?php

namespace Core\Elements\Table;

trait BackCompatMethods
{
    /**
     * Indicator this column is sortable.
     *
     * @param  bool  $sortable
     * @return $this
     *
     * @deprecated Use Column::sortable() method instead.
     */
    public function sort(bool $sortable = true)
    {
        return $this->sortable($sortable);
    }

    /**
     * Deprecated method.
     *
     * @param  callable  $callback
     * @return $this
     *
     * @deprecated Use Column::displayUsing() method instead.
     */
    public function render($callback)
    {
        return $this->displayUsing($callback);
    }
}
