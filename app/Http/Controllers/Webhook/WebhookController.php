<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Requests\OrderStatusRequest;
use App\Http\Resources\WebhookOrderResource;
use App\Services\WebhookService;
use Illuminate\Http\Request;
use Exception;

use App\Http\Controllers\Controller;


class WebhookController extends Controller
{
    public WebhookService $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }


    public function changeStatus($order_serial_no,  OrderStatusRequest $request)
    {
        try {
            return $this->webhookService->changeStatus($order_serial_no, $request);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function orders( Request $request)
    {
        try {
            return WebhookOrderResource::collection($this->webhookService->orders($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

}