<?php

namespace App\Orchid\Screens\Personality;

use App\Model\Personality;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\PersonalityRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var PersonalityRepository
     */
    protected $personalities;

    /**
     * Constructor.
     *
     * @param  PersonalityRepository  $personalities
     */
    public function __construct(PersonalityRepository $personalities)
    {
        parent::__construct();

        $this->personalities = $personalities;

        $this->name = __('Tính cách');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->personalities;
    }

    public function query(): array
    {
        $this->authorize(Personality::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Thêm mới'), 'platform.personalities.create')
                ->canSee($this->currentUserCan(Personality::PERMISSION_TOUCH))
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
