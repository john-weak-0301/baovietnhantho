<?php

namespace App\Orchid\Requests;

use App\Model\Fund;
use Illuminate\Foundation\Http\FormRequest;

class StoreFundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Fund::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'fund.name' => 'required|max:255',
            'fund.order' => 'nullable|int|max:100',
            'fund.risks_of_investing' => 'nullable',
            'fund.desc_target' => 'nullable',
            'fund.desc_profit' => 'nullable',
            'fund.desc_invest' => 'nullable',
        ];
    }
}
