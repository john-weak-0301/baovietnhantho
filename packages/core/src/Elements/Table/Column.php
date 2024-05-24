<?php

namespace Core\Elements\Table;

use Closure;
use Core\Elements\Element;
use Illuminate\Support\HtmlString;

class Column extends Element
{
    use BackCompatMethods;

    /* Constants */
    public const ALIGN_LEFT = 'left';
    public const ALIGN_CENTER = 'center';
    public const ALIGN_RIGHT = 'right';

    public const FILTER_TEXT = 'text';
    public const FILTER_NUMERIC = 'numeric';
    public const FILTER_DATE = 'date';

    /**
     * The attribute/column name of the model.
     *
     * @var string
     */
    public $column;

    /**
     * The displayable name of the column.
     *
     * @var string
     */
    public $name;

    /**
     * The column width.
     *
     * @var string
     */
    public $width;

    /**
     * The filter name.
     *
     * @var string
     */
    public $filter;

    /**
     * Indicates if the column should be sortable.
     *
     * @var bool
     */
    public $sortable = false;

    /**
     * The column align.
     *
     * @var string
     */
    public $align = 'left';

    /**
     * The callback to be used to resolve the column's display value.
     *
     * @var Closure
     */
    public $displayCallback;

    /**
     * Store the after render callback functions.
     *
     * @var array|Closure[]
     */
    protected $afterRenderCallbacks = [];

    /**
     * The column text after resolved.
     *
     * @var mixed
     */
    protected $resolvedText;

    /**
     * Create a new column.
     *
     * @param  string  $column
     * @param  string  $name
     * @return void
     */
    public function __construct(string $column, string $name)
    {
        $this->column = $column;

        $this->name = $name;

        if (method_exists($this, 'setup')) {
            $this->setup();
        }
    }

    /**
     * Sets the column width.
     *
     * @param  string  $width
     * @return $this
     */
    public function width(string $width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set the column filter type.
     *
     * Supported: "date", "text', "numeric"
     *
     * @param  string  $filter
     * @return $this
     */
    public function filter(string $filter = 'text')
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Indicator this column is sortable.
     *
     * @param  bool  $sortable
     * @return $this
     */
    public function sortable(bool $sortable = true)
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Sets the column align.
     *
     * @param  string  $align
     * @return $this
     */
    public function align(string $align)
    {
        $this->align = $align;

        return $this;
    }

    /**
     * Get the column value for display.
     *
     * @return mixed
     */
    public function getResolvedText()
    {
        return $this->resolvedText;
    }

    /**
     * Set the column that should be displayed.
     *
     * @param  Closure|string  $display
     * @param  mixed|null  $default
     * @return $this
     */
    public function displayUsing($display, $default = null)
    {
        $this->displayCallback = $display instanceof Closure
            ? $display
            : function ($resource) use ($display, $default) {
                return data_get($resource, $display, $default);
            };

        return $this;
    }

    /**
     * Wrap the column text a link.
     *
     * @param  string|callable  $link
     * @return $this
     */
    public function linkTo($link)
    {
        $this->afterRenderCallbacks['link'] = function ($text, $resource) use ($link) {
            $builder = LinkBuilder::createForBuilder($text);

            if (is_string($link)) {
                $builder->link($link);
            } elseif (is_callable($link)) {
                $response = $link($builder, $resource, $text);

                if ($response && is_string($resource) && !$builder->url()) {
                    $builder->link($response);
                }
            }

            return new HtmlString($builder->render());
        };

        return $this;
    }

    /**
     * Open a async modal.
     *
     * @param  string  $modal
     * @param  string  $method
     * @return $this
     */
    public function openModal(string $modal, $method)
    {
        $this->afterRenderCallbacks['link'] = function ($text, $resource) use ($modal, $method) {
            return new HtmlString(view('platform::partials.td.async', [
                'modal'      => $modal,
                'attributes' => [],
                'text'       => $text,
                'method'     => $method,
                'title'      => $this->name,
                'route'      => $this->asyncRoute ?? null,
            ])->render());
        };

        return $this;
    }

    /**
     * Resolve the field's value for display.
     *
     * @param  mixed  $resource
     * @param  string|null  $column
     * @return void
     */
    public function resolveForDisplay($resource, string $column = null): void
    {
        $column = $column ?? (string) $this->column;

        $text = $this->resolveAttribute($resource, $column);

        if (is_callable($this->displayCallback)) {
            $text = call_user_func($this->displayCallback, $resource, $text);
        }

        $text = array_reduce(
            array_values($this->afterRenderCallbacks),
            function ($currentText, $callback) use ($resource) {
                return is_callable($callback)
                    ? $callback($currentText, $resource)
                    : $currentText;
            },
            $text
        );

        $this->resolvedText = $text;
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, string $attribute)
    {
        return data_get($resource, str_replace('->', '.', $attribute));
    }
}
