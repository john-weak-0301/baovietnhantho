<?php

namespace App\Orchid\Requests;

use App\Model\Consultant;
use Illuminate\Foundation\Http\FormRequest;

class StoreConsultantRequest extends FormRequest
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
            'consultant.customer_name'      => 'required|min:3|max:100',
            'consultant.customer_phone'     => 'required',
            'consultant.customer_email'     => 'required|email',
            'consultant.customer_address'   => 'required|min:3|max:100',
            'consultant.private_note'       => 'nullable|min:3',
            'consultant.counselor_id'       => 'required|int|exists:counselors,id',
        ];
    }
}
