<?php

namespace App\Orchid\Requests;

use App\Model\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Page::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $slugRules = ['nullable', 'alpha_dash', 'min:3', 'max:100'];

        $editing = $this->route()->parameter('pageId');

        if ($editing) {
            $slugRules = array_merge($slugRules, [
                Rule::unique('pages', 'slug')->ignore($editing->id),
            ]);
        }

        return [
            'page.title'  => 'required|min:3|max:100',
            'page.slug'   => $slugRules,
            'page.status' => 'required',
        ];
    }
}
