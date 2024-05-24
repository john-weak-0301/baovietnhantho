<?php

namespace App\Orchid\Screens\News;

use App\Model\News;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\NewRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var NewRepository
     */
    protected $news;

    /**
     * Constructor.
     *
     * @param  NewRepository  $news
     */
    public function __construct(NewRepository $news)
    {
        parent::__construct();

        $this->news = $news;

        $this->name = __('Tin tức');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->news;
    }

    public function query(): array
    {
        $this->authorize(News::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Thêm mới'), 'platform.news.create')
                ->canSee($this->currentUserCan(News::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    public function layout(): array
    {
        return [
            new Layouts\ListTable,
        ];
    }
}
