<?php

namespace App\Orchid\Requests;

use App\Model\BranchService;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(BranchService::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'service.name'        => 'required|min:3|max:255',
            'service.description' => 'nullable|min:3',
        ];
    }
}
