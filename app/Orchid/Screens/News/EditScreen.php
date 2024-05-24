<?php

namespace App\Orchid\Screens\News;

use App\Dashboard\Layouts\SEOLayout;
use App\Model\News;
use App\Orchid\Screens\AbstractScreen;
use App\Orchid\Requests\StoreNewRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Layout;

class EditScreen extends AbstractScreen
{
    /**
     * Indicator the model's exists or not.
     *
     * @var bool
     */
    protected $exist = false;

    /**
     * @var News
     */
    protected $news;

    /**
     * Query data.
     *
     * @param  Request  $request
     * @param  News  $news
     * @return array
     */
    public function query(Request $request, News $news): array
    {
        $this->authorize(News::PERMISSION_TOUCH);

        $this->news  = $news;
        $this->exist = $news->exists;

        $category = $news->categories ? $news->categories->pluck('id')->all() : [];

        if ($this->exist) {
            $this->name = 'Sửa bài viết';
        } else {
            $this->name = 'Thêm bài viết';
        }

        return [
            'news'     => $news,
            'category' => $category,
            'seotools' => $news->getOptions()->get('seotools'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        $user = $this->request->user();

        return [
            $this->addLink(__('Tạo'))
                ->icon('icon-check')
                ->method('save')
                ->canSee(!$this->exist),

            $this->addLink(__('Chi tiết'))
                ->icon('icon-doc')
                ->link($this->news->url->link)
                ->canSee($this->exist),

            $this->addLink(__('Xóa'))
                ->icon('icon-trash')
                ->method('destroy')
                ->confirm(__('Bạn có muốn xóa?'))
                ->canSee($this->exist && $user->can(News::PERMISSION_DELETE)),

            $this->addLink(__('Lưu'))
                ->icon('icon-check')
                ->method('save')
                ->canSee($this->exist),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            Layout::tabs([
                'Nội dung' => Layouts\EditLayout::class,
                'SEO'      => SEOLayout::class,
            ]),
        ];
    }

    public function save(StoreNewRequest $request, News $news): RedirectResponse
    {
        $validated = $request->validated()['news'] ?? [];

        $news->fill([
            'image'          => sanitize_text_field($validated['image'] ?? ''),
            'image_slider'   => sanitize_text_field($validated['image_slider'] ?? ''),
            'title'          => sanitize_text_field($validated['title'] ?? ''),
            'slug'           => sanitize_text_field($validated['slug'] ?? ''),
            'excerpt'        => sanitize_textarea_field($validated['excerpt'] ?? ''),
            'is_featured'    => $request->input('news.is_featured') === null ? '0' : '1',
            'in_slider'      => $request->input('news.in_slider') === null ? '0' : '1',
            'status'         => sanitize_text_field($request->input('news.status') ?? 'pending'),
            'published_date' => $validated['published_date'] ?? null,
        ]);

        $news->options->set('show_toc', (bool) $request->input('news.options.show_toc'));
        $news->options->set('seotools', SEOLayout::values($request));
        $news->saveOrFail();

        $news->categories()->sync($request->validated()['category'] ?? []);
        $news->setTags($request->input('news.tags'));

        alert()->success(__('Lưu thành công.'));

        return redirect($news->url->edit);
    }

    public function destroy(News $news): RedirectResponse
    {
        $this->checkPermission(News::PERMISSION_DELETE);

        $news->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.news');
    }
}
