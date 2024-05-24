<?php

namespace App\Orchid\Requests;

use App\Model\Consultant;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Consultant::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'contact.name' => 'required|min:3|max:100',
            'contact.phone_number' => 'required',
            'contact.email' => 'required|email',
            'contact.address' => 'required|min:3|max:100',
            'contact.message' => 'required|min:10',
            'contact.province_code' => 'required',
        ];
    }
}
