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
        ];
    }
} 