<?php

namespace App\Orchid\Requests;

use App\Model\ServiceCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(ServiceCategory::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $slugRules = ['nullable', 'alpha_dash', 'min:3', 'max:100'];

        if ($editing = $this->route()->parameter('serviceCategoryId')) {
            $slugRules = array_merge($slugRules, [
                Rule::unique('service_categories', 'slug')->ignore($editing->id),
            ]);
        }

        return [
            'category.name'         => 'required|min:3|max:255',
            'category.description'  => 'nullable|min:3',
            'category.slug'         => $slugRules,
            'category.order'        => 'nullable|numeric|min:0',
            'category.icon'         => 'nullable|string',
            'category.show_in_menu' => 'nullable|boolean',
        ];
    }
}
