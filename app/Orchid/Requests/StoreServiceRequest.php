<?php

namespace App\Orchid\Requests;

use App\Model\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can(Service::PERMISSION_TOUCH);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $slugRules = ['nullable', 'alpha_dash', 'min:3', 'max:100'];

        $editing = $this->route()->parameter('serviceId');

        if ($editing) {
            $slugRules = array_merge($slugRules, [
                Rule::unique('services', 'slug')->ignore($editing->id),
            ]);
        }

        return [
            'service.title'  => 'required|min:3|max:255',
            'service.order'  => 'nullable|numeric|min:0',
            'service.slug'   => $slugRules,
            'service.status' => 'required',
            'category'       => 'int|exists:service_categories,id',
            'service.image'  => 'max:2048',
        ];
    }
}
