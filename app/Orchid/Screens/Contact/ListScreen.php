<?php

namespace App\Orchid\Screens\Contact;

use App\Model\Contact;
use App\Orchid\Screens\AbstractScreen;
use App\Orchid\Actions\DeleteAction;
use App\Repositories\ContactRepository;
use Core\Screens\InteractsWithActions;
use Core\Screens\HasRepository;
use Core\Database\Repository;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var ContactRepository
     */
    protected $contacts;

    /**
     * Constructor.
     *
     * @param ContactRepository $contacts
     */
    public function __construct(ContactRepository $contacts)
    {
        parent::__construct();

        $this->contacts = $contacts;

        $this->name = __('Liên hệ');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->contacts;
    }

    /**
     * @return array
     */
    public function query(): array
    {
        $this->authorize(Contact::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [
            new DeleteAction(),
        ];
    }

    public function commandBar(): array
    {
        return [];
    }

    public function layout(): array
    {
        return [
            (new Layouts\ListTable)->queryFrom($this->getRepository()),
        ];
    }
}
