<?php

namespace App\Dashboard\Screens;

use App\Model\Block;
use Core\Screens\Screen;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;
use Core\Dashboard\Actions\DeleteMediaAction;
use App\Repositories\BlockRepository;
use App\Dashboard\Layouts\Blocks\BlockListTable;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;

class BlockListScreen extends Screen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var BlockRepository
     */
    protected $blocks;

    /**
     * Constructor.
     *
     * @param  BlockRepository  $blocks
     */
    public function __construct(BlockRepository $blocks)
    {
        parent::__construct();

        $this->name   = __('Blocks');
        $this->blocks = $blocks;
    }

    /**
     * {@inheritdoc}
     */
    public function query(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        return [
            Link::name('Thêm block')
                ->icon('icon-plus')
                ->modal('create')
                ->method('store'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            new BlockListTable,

            Layout::modal('create', [
                Layout::rows([
                    Input::make('title')
                        ->title(__('Tên block'))
                        ->required(),
                ]),
            ]),
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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $block = new Block();

        $block->raw_title = clean($request->title);
        $block->status    = 'publish';

        $block->saveOrFail();

        return redirect()->back();
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->blocks;
    }
}
