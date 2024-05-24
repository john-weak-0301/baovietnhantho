<?php

namespace App\Orchid\Requests;

use App\Model\News;
use App\Model\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Product::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $isAddition = $this->type === 'addition';

        $slugRules = ['nullable', 'alpha_dash', 'min:3', 'max:100'];

        if ($editing = $this->route()->parameter('productId')) {
            $slugRules = array_merge($slugRules, [
                Rule::unique('products', 'slug')->ignore($editing->id),
            ]);
        }

        return [
            'product.slug'         => $slugRules,
            'product.title'        => 'required|min:3|max:255',
            'product.excerpt'      => 'nullable|min:3',
            'product.image'        => 'nullable',
            'product.order'        => 'nullable|int',
            'product.categories'   => $isAddition ? 'nullable' : 'required|array|min:1',
            'product.additions.*'  => $isAddition ? 'nullable' : 'int|exists:products,id',
            'product.categories.*' => 'int|exists:product_categories,id',
            'product.related.*'    => 'int|exists:products,id',
        ];
    }
}
