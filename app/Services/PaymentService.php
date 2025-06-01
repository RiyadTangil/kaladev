<?php

namespace App\Services;

use App\Events\SendOrderGotMail;
use App\Events\SendOrderGotSms;
use App\Events\SendOrderStatusToWebhook;
use App\Events\SendOrderSms;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use App\Events\SendOrderGotPush;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Transaction;
use App\Models\User;
use App\Enums\Ask;

class PaymentService
{
    public function payment($order, $gatewaySlug, $transactionNo)
    {
        $transaction = Transaction::where(['order_id' => $order->id])->first();
        if (!$transaction) {
            $transaction = Transaction::create([
                'order_id'       => $order->id,
                'transaction_no' => $transactionNo,
                'amount'         => $order->total,
                'payment_method' => $gatewaySlug,
                'sign'           => '+',
                'type'           => 'payment'
            ]);
        }
        $order->payment_status = PaymentStatus::PAID;
        $order->active         = Ask::YES;
        $order->save();

        SendOrderMail::dispatch(['order_id' => $order->id, 'status' => OrderStatus::PENDING]);
        SendOrderSms::dispatch(['order_id' => $order->id, 'status' => OrderStatus::PENDING]);
        SendOrderPush::dispatch(['order_id' => $order->id, 'status' => OrderStatus::PENDING]);

        SendOrderGotMail::dispatch(['order_id' => $order->id]);
        SendOrderGotSms::dispatch(['order_id' => $order->id]);
        SendOrderGotPush::dispatch(['order_id' => $order->id]);

        SendOrderStatusToWebhook::dispatch(['order_id' => $order->id]);

        return $transaction;
    }

    public function cashBack($order, $gatewaySlug, $transactionNo)
    {
        $transaction = Transaction::where(['order_id' => $order->id])->first();
        if ($transaction) {
            $transaction = Transaction::create([
                'order_id'       => $order->id,
                'transaction_no' => $transactionNo,
                'amount'         => $order->total,
                'payment_method' => $gatewaySlug,
                'sign'           => '-',
                'type'           => 'cash_back'
            ]);

            $user = User::find($order->user_id);
            if ($user) {
                $user->balance = ($user->balance + $order->total);
                $user->save();
            }
        }

        return $transaction;
    }
}
