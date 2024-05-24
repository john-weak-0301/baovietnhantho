<?php

namespace App\Orchid\Screens;

use App\Model\Product;
use Core\Elements\Fields\Field;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\TinyMCE;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Setting\Setting;
use Spatie\ResponseCache\Facades\ResponseCache;

class OptionsScreen extends Screen
{
    public function __construct()
    {
        parent::__construct();

        $this->name = __('Cài đặt hệ thống');
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return Setting::query()->pluck('value', 'key')->all();
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        return [
            Link::group([
                Link::name('Xóa cache')->method('clearCache'),
            ])->name('Hệ thống'),

            Link::name(__('Lưu'))
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
            Layout::tabs([
                'Giao diện' => Layout::rows([
                    TextArea::make('popular_tags')
                        ->title('Từ khóa phổ biến')
                        ->help('Phân cách các từ khóa bằng cách xuống dòng')
                        ->rows(5),

                    Quill::make('footer_copyright')
                        ->title('Nội dung footer copyright')
                        ->hr(),

                    CheckBox::make('enable_new_menu')
                        ->title('Menu')
                        ->help('Bật mega-menu mới'),
                ]),

                'Cấu hình' => Layout::rows([
                    Input::make('messenger_page_id')
                        ->title('Page ID cho Messenger')
                        ->help('Hướng dẫn tại <a target="_blank" href="https://developers.facebook.com/docs/messenger-platform/discovery/customer-chat-plugin">đây</a>')
                        ->hr(),

                    Input::make('recaptcha_sitekey')
                        ->value(env('NOCAPTCHA_SECRET'))
                        ->title('ReCaptcha (v2) Site Key'),

                    Input::make('recaptcha_secret')
                        ->value(env('NOCAPTCHA_SITEKEY'))
                        ->title('ReCaptcha (v2) Secret')
                        ->help('Lấy key tại <a target="_blank" href="https://www.google.com/recaptcha/admin">đây</a>'),
                ]),

                'Banner' => Layout::rows([
                    TinyMCE::make('global_banner')
                        ->title('Banner hiển thị sidebar tin tức')
                        ->help('Có thể điền shortcode block banner vào VD: <code>[block id="1"]</code>'),
                ]),

                'Sản phẩm đề xuất' => Layout::rows(
                    $this->getSuggestedProductsFields()
                ),
            ]),
        ];
    }

    protected function getSuggestedProductsFields(): array
    {
        $fields = [];

        foreach (config('press.objectives') as $id => $value) {
            $fields[] = Relation::make('suggested_'.$id.'.')
                ->title($value['name'] ?? $id)
                ->multiple()
                ->fromModel(Product::class, 'title', 'id');
        }

        return $fields;
    }

    /**
     * //
     *
     * @return array
     */
    protected function getSanitizations(): array
    {
        return [
            'global_banner' => 'wp_kses_post',
            'popular_tags' => 'sanitize_textarea_field',
            'enable_new_menu' => 'sanitize_text_field',
            'messenger_page_id' => 'sanitize_textarea_field',
            'footer_copyright' => function ($value) {
                $value = trim(wp_kses_post($value));

                if (!strip_all_tags($value)) {
                    return null;
                }

                return $value;
            },
        ];
    }

    /**
     * //
     *
     * @return array
     */
    protected function getValidationRules(): array
    {
        return [
            'enable_new_menu' => 'nullable|bool',
            'footer_copyright' => 'nullable|string',
            'popular_tags' => 'nullable|string',
            'messenger_page_id' => 'nullable|string',
            'global_banner' => 'nullable|string',
            'recaptcha_sitekey' => 'nullable|string',
            'recaptcha_secret' => 'nullable|string',
            'suggested_y-te.*' => 'nullable|int|exists:products,id',
            'suggested_tai-chinh.*' => 'nullable|int|exists:products,id',
            'suggested_giao-duc.*' => 'nullable|int|exists:products,id',
            'suggested_dau-tu.*' => 'nullable|int|exists:products,id',
            'suggested_huu-tri.*' => 'nullable|int|exists:products,id',
        ];
    }

    /**
     * Handle save options.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $validated = $request->validate(
            $this->getValidationRules()
        );

        $settings = new Setting;

        $saved = 0;

        foreach ($validated as $key => $value) {
            $value = $this->castSavingValue($value, $key);

            cache()->forget('settings-'.$key);

            if ($value === null || $value === '') {
                $settings->forget($key);
                continue;
            }

            if ($settings->set($key, $value)) {
                $saved++;
            }
        }

        cache()->forget(
            'settings-suggested_y-te,suggested_tai-chinh,suggested_giao-duc,suggested_dau-tu,suggested_huu-tri'
        );

        if ($saved > 0) {
            alert()->success(__('Thay đổi thành công'));
        }

        return redirect()->route('platform.systems.options');
    }

    /**
     * //
     *
     * @param mixed $value
     * @param string $key
     * @return mixed
     */
    protected function castSavingValue($value, $key)
    {
        $sanitizations = $this->getSanitizations();

        if (false === $value) {
            $value = null;
        } elseif (is_scalar($value)) {
            $value = (string) $value;
        }

        if (isset($sanitizations[$key])) {
            $value = call_user_func($sanitizations[$key], $value);
        } else {
            $value = map_deep($value, 'clean');
        }

        return $value;
    }

    public function clearCache()
    {
        try {
            ResponseCache::clear();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            alert()->success('Xóa cache thành công');
        } catch (Exception $e) {
            report($e);
            alert()->error('Có lỗi xảy ra, vui lòng thử xóa bằng dòng lệnh: '.$e->getMessage());
        }

        return redirect()->back();
    }
}
