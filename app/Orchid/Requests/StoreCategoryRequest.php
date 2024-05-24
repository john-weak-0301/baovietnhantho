<?php

namespace App\Orchid\Requests;

use App\Model\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Category::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $slugRules = ['nullable', 'alpha_dash', 'min:3', 'max:100'];

        $editing = $this->route()->parameter('categoryId');

        if ($editing) {
            $slugRules = array_merge($slugRules, [
                Rule::unique('categories', 'slug')->ignore($editing->id),
            ]);
        }

        return [
            'category.name'        => 'required|min:3|max:255',
            'category.description' => 'nullable|min:3',
            'category.slug'        => $slugRules,
        ];
    }
}
