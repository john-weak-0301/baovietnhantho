<?php

namespace App\Orchid\Requests;

use App\Model\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(ProductCategory::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $slugRules = ['nullable', 'alpha_dash', 'min:3', 'max:100'];

        if ($editing = $this->route()->parameter('productCategoryId')) {
            $slugRules = array_merge($slugRules, [
                Rule::unique('product_categories', 'slug')->ignore($editing->id),
            ]);
        }

        return [
            'category.slug'        => $slugRules,
            'category.name'        => 'required|min:3|max:100',
            'category.description' => 'nullable|min:3',
            'category.order'       => 'nullable|numeric',
            'category.subtitle'    => 'nullable|string',
            'category.parent_id'   => 'nullable|int|exists:product_categories,id',
        ];
    }
}
