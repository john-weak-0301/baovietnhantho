<?php

namespace Core\Dashboard\Screens;

use Core\Screens\Screen;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;
use Core\Media\MediaRepository;
use Core\Database\Repository;
use Core\Dashboard\Layouts\MediaListTable;
use Core\Dashboard\Actions\DeleteMediaAction;
use Illuminate\Http\Request;
use Orchid\Screen\Link;

class MediaLibraryScreen extends Screen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var MediaRepository
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param  MediaRepository  $repository
     */
    public function __construct(MediaRepository $repository)
    {
        parent::__construct();

        $this->name       = __('Media');
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function query(Request $request): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        return [
            Link::name(__('Upload'))
                ->icon('icon-cloud-upload')
                ->link(route('dashboard.media.new')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            (new MediaListTable)
                ->queryFrom($this->repository)
                ->withActions($this->availableActions()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            (new DeletemediaAction)->withoutActionEvents(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }
}
