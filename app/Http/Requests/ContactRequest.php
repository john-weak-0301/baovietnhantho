<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * @var string
     */
    protected $redirectRoute = 'contact';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'phone_number' => 'required|phone:VN',
            'email' => 'nullable|email',
            'address' => 'nullable|min:3|max:255',
            'province_code' => 'nullable|required',
            'message' => 'required|min:10',
            'g-recaptcha-response' => config('captcha.sitekey') ? 'required|captcha' : 'nullable',
        ];
    }
}
