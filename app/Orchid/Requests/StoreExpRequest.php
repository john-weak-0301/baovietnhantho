<?php

namespace App\Orchid\Requests;

use App\Model\Experience;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Experience::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $slugRules = ['nullable', 'alpha_dash', 'min:3', 'max:100'];

        $editing = $this->route()->parameter('expId');

        if ($editing) {
            $slugRules = array_merge($slugRules, [
                Rule::unique('news', 'slug')->ignore($editing->id),
            ]);
        }

        return [
            'exp.title'          => 'required|min:3|max:255',
            'exp.slug'           => $slugRules,
            'exp.excerpt'        => 'nullable|min:3',
            'exp.image'          => 'nullable',
            'exp.image_slider'   => 'nullable',
            'exp.tags'           => 'array',
            'exp.tags.*'         => 'string|min:3',
            'exp.published_date' => 'nullable|date',
            'categories'         => 'required|array|exists:categories,id',
        ];
    }
}
