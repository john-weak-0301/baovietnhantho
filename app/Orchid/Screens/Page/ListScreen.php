<?php

namespace App\Orchid\Screens\Page;

use App\Model\Page;
use App\Model\User;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\PageRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;
use Illuminate\Http\Request;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var PageRepository
     */
    protected $pages;

    /**
     * Constructor.
     *
     * @param  PageRepository  $pages
     */
    public function __construct(PageRepository $pages)
    {
        parent::__construct();

        $this->pages = $pages;

        $this->name = __('Trang');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->pages;
    }

    public function query(): array
    {
        $this->authorize(Page::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Thêm mới'), 'platform.pages.create')
                ->canSee($this->currentUserCan(Page::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    public function layout(): array
    {
        return [
            (new Layouts\ListTable)
                ->queryFrom($this->getRepository()),
        ];
    }
}
