<?php

namespace App\Orchid\Screens\Page;

use App\Model\Page;
use App\Orchid\Fields\OpenEditor;
use App\Orchid\Screens\AbstractScreen;
use App\Orchid\Screens\Page\Layouts\HomeSliderForm;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;

class HomeScreen extends AbstractScreen
{
    /**
     * @var Page
     */
    protected $home;

    public function __construct()
    {
        $this->name = __('Trang chủ');

        parent::__construct();
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $this->home = Page::firstOrCreate(['type' => 'home'], ['title' => 'Index']);

        $options = $this->home->getOptions();

        return [
            'page'    => $this->home,
            'sliders' => $options->get('sliders') ?: [],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        return [
            $this->addLink(__('Save'))
                 ->icon('icon-check')
                 ->method('save'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                OpenEditor::make(route('platform.pages.editor', $this->home)),
            ]),

            Layout::view('platform.container.fields.home-sliders'),
        ];
    }

    public function save(Request $request)
    {
        $page = Page::where(['type' => 'home'])->firstOrFail();

        $page->options->sliders = $request->input('sliders');

        $page->saveOrFail();
        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.home');
    }
}
