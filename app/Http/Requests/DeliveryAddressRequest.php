<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'label'     => ['required', 'string', 'max:190'],
            'latitude'  => ['required', 'max:190'],
            'longitude' => ['required', 'max:190'],
            'address'   => ['required', 'string', 'max:500'],
            'apartment' => ['nullable', 'string', 'max:200'],
        ];
    }
}
