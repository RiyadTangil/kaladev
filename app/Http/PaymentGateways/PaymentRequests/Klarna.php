<?php

namespace App\Http\PaymentGateways\PaymentRequests;

use Illuminate\Foundation\Http\FormRequest;

class Klarna extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'stripeToken' => ['nullable', 'string'],
            'use_checkout' => ['nullable', 'string'],
            'klarna_only' => ['nullable', 'string'],
            'order' => ['nullable', 'array'],
            'order.currency' => ['nullable', 'string'],
            'order.amount' => ['nullable', 'numeric'],
        ];
    }
}