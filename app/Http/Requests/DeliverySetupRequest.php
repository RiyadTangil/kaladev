<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliverySetupRequest extends FormRequest
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
            'kilometer_range'      => ['required', 'numeric'],
            'minimum_order_amount' => ['required', 'numeric'],
            'delivery_charge'      => ['required', 'numeric'],
        ];
    }
}