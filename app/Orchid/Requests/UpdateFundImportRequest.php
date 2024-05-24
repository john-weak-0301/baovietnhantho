<?php

namespace App\Orchid\Requests;

use App\Model\Fund;
use App\Model\FundImport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateFundImportRequest extends FormRequest
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
            'fundImport.status' => [
                'required',
                Rule::in(FundImport::STATUS_KEYS),
            ],
        ];
    }
}
