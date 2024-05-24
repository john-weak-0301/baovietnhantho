<?php

namespace App\Orchid\Screens\Experience;

use App\Dashboard\Layouts\SEOLayout;
use App\Model\Experience;
use App\Orchid\Requests\StoreExpRequest;
use App\Orchid\Screens\AbstractScreen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * @var Experience
     */
    protected $exp;

    /**
     * Query data.
     *
     * @param  Request  $request
     * @param  Experience  $exp
     * @return array
     */
    public function query(Request $request, Experience $exp): array
    {
        $this->authorize(Experience::PERMISSION_TOUCH);

        $this->exp   = $exp;
        $this->exist = $exp->exists;

        $categories = $exp->categories ? $exp->categories->pluck('id')->all() : [];

        if ($this->exist) {
            $this->name = 'Sửa bài viết';
        } else {
            $this->name = 'Thêm bài viết';
        }

        return [
            'exp'        => $exp,
            'categories' => $categories,
            'seotools'   => $exp->getOptions()->get('seotools'),
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
                ->link($this->exp->url->link)
                ->canSee($this->exist),

            $this->addLink(__('Xóa'))
                ->icon('icon-trash')
                ->method('destroy')
                ->confirm(__('Bạn có muốn xóa?'))
                ->canSee($this->exist && $user->can(Experience::PERMISSION_DELETE)),

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

    public function save(StoreExpRequest $request, Experience $exp): RedirectResponse
    {
        $validated = $request->validated()['exp'] ?? [];

        $exp->fill([
            'image'          => sanitize_text_field($validated['image'] ?? ''),
            'image_slider'   => sanitize_text_field($validated['image_slider'] ?? ''),
            'title'          => sanitize_text_field($validated['title'] ?? ''),
            'slug'           => sanitize_text_field($validated['slug'] ?? ''),
            'excerpt'        => sanitize_textarea_field($validated['excerpt'] ?? ''),
            'is_featured'    => $request->input('exp.is_featured') === null ? '0' : '1',
            'in_slider'      => $request->input('exp.in_slider') === null ? '0' : '1',
            'status'         => sanitize_text_field($request->input('exp.status') ?? 'pending'),
            'published_date' => $validated['published_date'] ?? null,
        ]);

        $exp->options->set('show_toc', (bool) $request->input('exp.options.show_toc'));
        $exp->options->set('seotools', SEOLayout::values($request));
        $exp->saveOrFail();

        $exp->categories()->sync($request->validated()['categories'] ?? []);
        $exp->setTags($request->input('exp.tags'));

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.exps.edit', $exp);
    }

    public function destroy(Experience $exp): RedirectResponse
    {
        $this->checkPermission(Experience::PERMISSION_DELETE);

        $exp->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.exps');
    }
}
