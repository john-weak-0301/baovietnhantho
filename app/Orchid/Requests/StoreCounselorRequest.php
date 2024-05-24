<?php

namespace App\Orchid\Requests;

use App\Model\Counselor;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCounselorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Counselor::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $uid = ['required', 'alpha_dash', 'min:3', 'max:60'];

        if ($editing = $this->route()->parameter('counselorId')) {
            $uid = array_merge($uid, [
                Rule::unique('counselors', 'uid')->ignore($editing->id),
            ]);
        }

        return [
            'counselor.uid'           => $uid,
            'counselor.company_name'  => 'required|string|max:100',
            'counselor.first_name'    => 'required|min:2|max:100',
            'counselor.last_name'     => 'required|min:2|max:100',
            'counselor.display_name'  => 'nullable|min:3|max:100',
            'counselor.year_of_birth' => 'nullable|numeric',
            'counselor.avatar'        => 'max:2048',
            'counselor.gender'        => 'required',
            'counselor.rate_value'    => 'required|numeric',
            'personality'             => 'nullable|array|exists:personalities,id',
            '__area'                  => 'required|array',
            '__area.province'         => 'required|numeric',
            '__area.district'         => 'required|numeric',
        ];
    }
}
