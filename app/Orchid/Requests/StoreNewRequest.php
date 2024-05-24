<?php

namespace App\Orchid\Requests;

use App\Model\News;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(News::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $slugRules = ['nullable', 'alpha_dash', 'min:3', 'max:100'];

        $editing = $this->route()->parameter('newsId');

        if ($editing) {
            $slugRules = array_merge($slugRules, [
                Rule::unique('news', 'slug')->ignore($editing->id),
            ]);
        }

        return [
            'news.title'          => 'required|min:3|max:255',
            'news.slug'           => $slugRules,
            'news.excerpt'        => 'nullable|min:3',
            'news.image'          => 'nullable',
            'news.image_slider'   => 'nullable',
            'news.tags'           => 'array',
            'news.tags.*'         => 'string|min:3',
            'news.published_date' => 'nullable|date',
            'category'            => 'required|array|exists:categories,id',
        ];
    }
}
