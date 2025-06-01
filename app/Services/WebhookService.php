<?php

namespace App\Services;
use App\Enums\Ask;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use App\Events\SendOrderSms;
use App\Http\Requests\OrderStatusRequest;
use App\Models\Branch;
use App\Models\Order;
use Exception;
use Log;
use Illuminate\Http\Request;



class WebhookService
{
    public function __construct()
    {
    }

    public function branches()
    {
        try {
            return Branch::orderBy('id', 'desc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function branch(Branch $branch): Branch
    {
        try {
            return $branch;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function branchOrders(Branch $branch, Request $request)
    {
        try {

            $limit      = $request->get('limit', 25);

            return Order::withoutGlobalScope(Branch::class)
                ->with('orderItems', 'user', 'address')
                ->where('branch_id', $branch->id)
                ->where('active', Ask::YES)
                ->whereIn('order_type', [OrderType::DELIVERY, OrderType::TAKEAWAY])
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function orders( Request $request)
    {
        try {

            $limit      = $request->get('limit', 25);

            return Order::withoutGlobalScope(Branch::class)
                ->with('orderItems', 'user', 'address')
                ->where('active', Ask::YES)
                ->whereIn('order_type', [OrderType::DELIVERY, OrderType::TAKEAWAY])
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function changeStatus($order_serial_no, OrderStatusRequest $request): Order|array | \Illuminate\Http\Response
    {
        try {

            $order = Order::where('order_serial_no', $order_serial_no)->first();
            if (!$order) {
                throw new Exception('Order not found !!');
            }

            if ($request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED) {

                if ($order->transaction) {
                    app(PaymentService::class)->cashBack(
                        $order,
                        'credit',
                        rand(111111111111111, 99999999999999)
                    );
                }
            }


            // Only apply for first time once . When: Pending to accept. 
            if (
                $order->status == OrderStatus::PENDING && $request->status == OrderStatus::ACCEPT &&
                $order->order_type != OrderType::POS && $order->order_type != OrderType::DINING_TABLE
            ) {
                // gift / decrease  points to customer
                app(PointService::class)->calculatePoints($order);
            }


            // Return Order :  Remove gift , give back applied points . 
            if ($request->status == OrderStatus::RETURNED && $order->order_type != OrderType::POS && $order->order_type != OrderType::DINING_TABLE) {
                app(PointService::class)->returnPoints($order);
            }



            SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
            SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
            SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
            $order->status = $request->status;
            $order->save();
           

            return response(['status' => true, 'message' => 'Order status changed successfully'], 200);

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
