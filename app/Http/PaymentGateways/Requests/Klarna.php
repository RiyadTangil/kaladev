<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;

class Klarna
{
    public function rules(): array
    {
        return [
            'payment_type' => 'required|string',
            'klarna_status' => 'required|numeric',
            'klarna_fee_type' => 'nullable|string',
            'klarna_fee_amount' => 'nullable|numeric',
        ];
    }

    public function gateway(): array
    {
        return [
            'slug' => 'klarna',
            'status' => Activity::ENABLE,
        ];
    }

    public function gatewayOptions(): array
    {
        return [
            [
                'option' => 'klarna_status',
                'value' => request('klarna_status'),
            ],
            [
                'option' => 'klarna_fee_type',
                'value' => request('klarna_fee_type'),
            ],
            [
                'option' => 'klarna_fee_amount',
                'value' => request('klarna_fee_amount'),
            ],
        ];
    }
} 