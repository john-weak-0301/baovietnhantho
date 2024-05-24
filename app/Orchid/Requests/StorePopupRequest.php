<?php

namespace App\Orchid\Requests;

use App\Model\Popup;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePopupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Popup::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'popup.title1' => 'required',
            'popup.title2' => 'nullable',
            'popup.order' => 'required',
            'popup.layout' => [
                'required',
                Rule::in(['left', 'right'])
            ],
            'popup.image' => 'required',
            'popup.cta_link' => 'nullable',
            'popup.cta_text' => 'required',
            'popup.description' => 'nullable',
            'popup.show_more_links' => 'nullable',
            // 'popup.show_all' => [
            //     'nullable',
            //     Rule::in([0, 1])
            // ],
            // 'popup.show_products' => [
            //     'nullable',
            //     Rule::in([0, 1])
            // ],
            // 'popup.show_posts' => [
            //     'nullable',
            //     Rule::in([0, 1])
            // ],
            // 'popup.show_pages' => [
            //     'nullable',
            //     Rule::in([0, 1])
            // ],
            // 'popup.show_home_page' => [
            //     'nullable',
            //     Rule::in([0, 1])
            // ],
        ];
    }
}
