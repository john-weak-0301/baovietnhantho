<?php

namespace App\Orchid\Requests;

use App\Model\Branch;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Branch::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'branch.address' => 'required|min:3|max:100',
            'branch.province_code' => 'required',
            'branch.name' => 'required|min:3|max:100',
            'branch.location' => 'required',
            'branch.phone_number' => 'required',
            'branch.fax_number' => 'nullable',
            'branch.email' => 'required|email',
            'branch.type' => 'required',
            'branch.working_days.*.*' => 'required',
            'service' => 'required|exists:branchs_services,id',
        ];
    }
}
