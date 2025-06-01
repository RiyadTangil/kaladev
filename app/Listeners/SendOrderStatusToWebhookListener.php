<?php

namespace App\Listeners;

use App\Services\OrderStatusToWebhooklNotificationBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendOrderStatusToWebhookListener implements ShouldQueue
{

    public function handle($event)
    {
        try{
            $orderStatusToWebhooklNotificationBuilder = new OrderStatusToWebhooklNotificationBuilder($event->info);
            $orderStatusToWebhooklNotificationBuilder->send();
        } catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
