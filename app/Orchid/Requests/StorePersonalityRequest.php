<?php

namespace App\Orchid\Requests;

use App\Model\Personality;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonalityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Personality::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $slugRules = ['nullable', 'alpha_dash', 'min:3', 'max:100'];

        $editing = $this->route()->parameter('personalityId');

        if ($editing) {
            $slugRules = array_merge($slugRules, [
                Rule::unique('personalities', 'slug')->ignore($editing->id),
            ]);
        }

        return [
            'personality.name'        => 'required|min:3|max:255',
            'personality.description' => 'nullable|min:3',
            'personality.slug'        => $slugRules,
        ];
    }
}
