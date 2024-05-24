<?php

namespace Core\Elements\Table;

use Core\Screens\HasRepository;
use Core\Screens\Screen;
use Core\Support\Util;
use Core\Database\Repository;
use Core\Elements\FiltersLayout;
use Orchid\Screen\Layouts\Base;
use Orchid\Screen\Repository as Data;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Pagination\Paginator;

abstract class Table extends Base
{
    use InteractsWithColumns,
        InteractsWithActions,
        InteractsWithFilters;

    /**
     * The current request.
     *
     * @var Request
     */
    protected $request;

    /**
     * The current screen instance.
     *
     * @var Screen|null
     */
    protected $screen;

    /**
     * Store the items data.
     *
     * @var Collection|array|mixed
     */
    protected $items;

    /**
     * Indicator showing the search input.
     *
     * @var bool
     */
    public $showSearchInput = false;

    /**
     * Constructor.
     *
     * @param  Request|null  $request
     * @param  Screen|null  $screen
     */
    public function __construct(Request $request = null, Screen $screen = null)
    {
        $this->request = $request ?: request();

        if ($screen === null) {
            $screen = $this->request->route()->getController();
        }

        $this->screen = $screen instanceof Screen ? $screen : null;

        if (empty($this->data) && $this->screen &&
            $this->screen instanceof HasRepository) {
            $this->queryFrom($this->screen->getRepository());
        }
    }

    /**
     * Query the items from a resource repository.
     *
     * @param  Repository  $repository
     * @return $this
     */
    public function queryFrom(Repository $repository)
    {
        if ($this->items !== null) {
            return $this;
        }

        $this->showSearchInput = $repository->searchable();

        $request = $this->request;
        $perPage = (int) $this->request->get('perPage', 25);

        $query = $repository->query($request, $request->search, $request->trashed);

        if (method_exists($this, 'prepare')) {
            $this->prepare($this->request, $query);
        }

        foreach ($this->availableFilters() as $filter) {
            $query = $filter->filter($query);
        }

        $this->items = $query->paginate(min($perPage, 50));

        return $this;
    }

    /**
     * Shoudl show the search.
     *
     * @param  bool  $show
     * @return $this
     */
    public function showSearchInput($show = true)
    {
        $this->showSearchInput = $show;

        return $this;
    }

    /**
     * Build the table.
     *
     * @param  Data  $data
     * @return mixed
     *
     * @throws \Throwable
     */
    public function build(Data $data)
    {
        if (!$this->items) {
            $this->items = $data->getContent($this->data ?? 'table') ?? [];
        }

        return view('core::layouts.table.table', [
            'table'   => $this,
            'items'   => $this->items,
            'columns' => $this->availableColumns(),
            'filters' => $this->renderFilters($data),
        ])->render();
    }

    /**
     * @param  Data  $data
     * @return string
     */
    public function renderFilters(Data $data)
    {
        return (new FiltersLayout(
            $this->availableFilters()->all()
        ))->build($data);
    }

    /**
     * Determines if should render the pagination.
     *
     * @return bool
     */
    public function shoudPagination(): bool
    {
        return $this->items instanceof Paginator;
    }

    /**
     * Generate the header rows.
     *
     * @return string
     *
     * @throws \Throwable
     */
    public function headerRows()
    {
        return view('core::layouts.table.header', [
            'table'   => $this,
            'columns' => $this->availableColumns(),
        ])->render();
    }

    /**
     * Generate the body rows.
     *
     * @return string
     */
    public function bodyRows(): string
    {
        $columns = $this->availableColumns();

        if (blank($this->items)) {
            return new HtmlString(
                sprintf(
                    '<tr class="no-items"><td class="colspanchange" colspan="%2$d">%1$s</td></tr>',
                    $this->noItems(),
                    count($columns)
                )
            );
        }

        return $this->generateRows($columns);
    }

    /**
     * Message to be displayed when there are no items.
     *
     * @return string
     */
    public function noItems(): string
    {
        return sprintf(
            '<h3 class="font-thin text-center pt-5 pb-5"><i class="icon-table block m-b"></i><span>%s</span></h3>',
            __('Chưa có dữ liệu nào')
        );
    }

    /**
     * Generate the table rows.
     *
     * @param  Collection|Column[]  $columns
     * @return HtmlString|string
     */
    public function generateRows($columns): string
    {
        $rows = '';

        if (!is_iterable($this->items)) {
            return $rows;
        }

        foreach ($this->items as $resource) {
            $rows .= '<tr>';

            foreach ($columns as $column) {
                $column->resolveForDisplay($resource);
                $rows .= $this->generateColumn($column, $resource);
            }

            $rows .= '</tr>';
        }

        return new HtmlString($rows);
    }

    /**
     * Generate the table column.
     *
     * @param  Column  $column
     * @param  mixed  $resource
     * @return string
     */
    public function generateColumn(Column $column, $resource): string
    {
        $attributes = [];

        $attributes['class'] = $this->getColumnClasses($column);

        // Comments column uses HTML in the display name with screen reader text.
        // Instead of using esc_attr(), we strip tags to get closer to a user-friendly string.
        $attributes['data-colname'] = strip_all_tags($column->name);

        $text = $column->getResolvedText();

        if ($text instanceof Renderable) {
            $text = new HtmlString($text->render());
        }

        return sprintf('<td %1$s>%2$s</td>', Util::buildHtmlAttributes($attributes), e($text));
    }

    /**
     * Build the column classes.
     *
     * @param  Column  $column
     * @return string
     */
    public function getColumnClasses(Column $column)
    {
        return classnames([
            'check-column' => $column instanceof ID,
            'column-'.$column->column,
            $column->align ? 'text-'.$column->align : '',
            $column->classes ?? '',
        ]);
    }
}
