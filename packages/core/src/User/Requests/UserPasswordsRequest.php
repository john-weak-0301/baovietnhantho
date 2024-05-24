<?php

namespace Core\User\Requests;

use App\Rules\CurrentPassword;
use Illuminate\Foundation\Http\FormRequest;

class UserPasswordsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'current_password' => [$this->user()->password ? 'required' : 'nullable', new CurrentPassword],
            'password'         => 'required|min:6|confirmed',
        ];
    }
}
