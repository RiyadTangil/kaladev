<?php

namespace App\Services;


use App\Enums\Ask;
use App\Events\SendOrderGotMail;
use App\Events\SendOrderGotSms;
use App\Events\SendOrderStatusToWebhook;
use App\Models\OrderPointDiscount;
use Exception;
use App\Models\Tax;
use App\Models\Item;
use App\Enums\TaxType;
use App\Models\Address;
use App\Enums\OrderType;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Models\OrderCoupon;
use App\Events\SendOrderSms;
use App\Models\OrderAddress;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use App\Libraries\AppLibrary;
use App\Models\FrontendOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaginateRequest;
use Smartisan\Settings\Facades\Settings;
use App\Http\Requests\OrderStatusRequest;
use App\Events\SendOrderGotPush;
use App\Services\PaymentMethodFeeService;

class FrontendOrderService
{

    public object $frontendOrder;
    protected array $frontendOrderFilter = [
        'order_serial_no',
        'user_id',
        'branch_id',
        'total',
        'order_type',
        'order_datetime',
        'payment_method',
        'payment_status',
        'status',
        'active',
        'delivery_boy_id'
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function myOrder(PaginateRequest $request)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $frontendOrderColumn = $request->get('order_column') ?? 'id';
            $frontendOrderType = $request->get('order_by') ?? 'desc';

            return FrontendOrder::where('order_type', "!=", OrderType::POS)->where(function ($query) use ($requests) {
                $query->where('user_id', auth()->user()->id);
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->frontendOrderFilter)) {
                        if ($key === "status" || $key === "active") {
                            $query->where($key, (int) $request);
                        } else {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('status', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($frontendOrderColumn, $frontendOrderType)->$method(
                    $methodValue
                );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function myOrderStore(OrderRequest $request): object
    {
        try {
            DB::transaction(function () use ($request) {
                $oldOrder     = FrontendOrder::where(['user_id' => Auth::user()->id, 'active' => Ask::NO ]);
                $orderReplace = $oldOrder;
                if (!blank($oldOrder->get())) {
                    $ids          = $oldOrder->pluck('id');
                    OrderItem::whereIn('order_id', $ids)?->delete();
                    OrderAddress::whereIn('order_id', $ids)->where(['user_id' => Auth::user()->id])?->delete();
                    OrderPointDiscount::whereIn('order_id', $ids)->where(['user_id' => Auth::user()->id])?->delete();
                    OrderCoupon::whereIn('order_id', $ids)->where(['user_id' => Auth::user()->id])?->delete();
                    $orderReplace->delete();
                }

                $this->frontendOrder = FrontendOrder::create(
                    $request->validated() + [
                        'user_id' => Auth::user()->id,
                        'status' => OrderStatus::PENDING,
                        'order_datetime' => date('Y-m-d H:i:s'),
                        'preparation_time' => Settings::group('order_setup')->get('order_setup_food_preparation_time')
                    ]
                );

                $i = 0;
                $totalTax = 0;
                $itemsArray = [];
                $requestItems = json_decode($request->items);
                $items = Item::get()->pluck('tax_id', 'id');
                $taxes = AppLibrary::pluck(Tax::get(), 'obj', 'id');

                if (!blank($requestItems)) {
                    foreach ($requestItems as $item) {
                        $taxId = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                        $taxName = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                        $taxRate = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                        $taxType = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                        $taxPrice = $taxType === TaxType::FIXED ? $taxRate : ($item->total_price * $taxRate) / 100;
                        $itemsArray[$i] = [
                            'order_id' => $this->frontendOrder->id,
                            'branch_id' => $item->branch_id,
                            'item_id' => $item->item_id,
                            'quantity' => $item->quantity,
                            'discount' => (float) $item->discount,
                            'tax_name' => $taxName,
                            'tax_rate' => $taxRate,
                            'tax_type' => $taxType,
                            'tax_amount' => $taxPrice,
                            'price' => $item->item_price,
                            'item_variations' => json_encode($item->item_variations),
                            'item_extras' => json_encode($item->item_extras),
                            'instruction' => $item->instruction,
                            'item_variation_total' => $item->item_variation_total,
                            'item_extra_total' => $item->item_extra_total,
                            'total_price' => $item->total_price,
                        ];
                        $totalTax = $totalTax + $taxPrice;
                        $i++;
                    }
                }

                if (!blank($itemsArray)) {
                    OrderItem::insert($itemsArray);
                }

                // Calculate payment method fee if payment method is provided
                $paymentMethodFee = 0;
                if ($request->payment_method && $request->payment_method > 0) {
                    $paymentFeeService = new PaymentMethodFeeService();
                    $orderAmountForFee = $this->frontendOrder->subtotal + $totalTax + $this->frontendOrder->delivery_charge - $this->frontendOrder->discount - ($this->frontendOrder->point_discount_amount ?? 0);
                    $paymentMethodFee = $paymentFeeService->calculateFee($request->payment_method, $orderAmountForFee);
                }

                $this->frontendOrder->order_serial_no = date('dmy') . $this->frontendOrder->id;
                $this->frontendOrder->total_tax = $totalTax;
                $this->frontendOrder->payment_method_fee = $paymentMethodFee;
                
                // Recalculate total with payment method fee
                if ($paymentMethodFee > 0) {
                    $this->frontendOrder->total = $this->frontendOrder->subtotal + $totalTax + $this->frontendOrder->delivery_charge + $paymentMethodFee + ($this->frontendOrder->rider_tip ?? 0) - $this->frontendOrder->discount - ($this->frontendOrder->point_discount_amount ?? 0);
                }
                
                $this->frontendOrder->save();

                if ($request->address_id) {
                    $address = Address::find($request->address_id);
                    if ($address) {
                        OrderAddress::create([
                            'order_id' => $this->frontendOrder->id,
                            'user_id' => Auth::user()->id,
                            'label' => $address->label,
                            'address' => $address->address,
                            'apartment' => $address->apartment,
                            'latitude' => $address->latitude,
                            'longitude' => $address->longitude
                        ]);
                    }
                }

                if ($request->coupon_id > 0) {
                    OrderCoupon::create([
                        'order_id' => $this->frontendOrder->id,
                        'coupon_id' => $request->coupon_id,
                        'user_id' => Auth::user()->id,
                        'discount' => $request->discount
                    ]);
                }

                if ($request->applied_points > 0 && $request->point_discount_amount < $request->subtotal) {
                    $user = Auth::user();

                    $pending_applied_points = FrontendOrder::where([
                        'user_id' => $user->id,
                        'status' => OrderStatus::PENDING,
                        'active' => Ask::YES
                    ])
                    ->with('pointDiscount') 
                    ->get() 
                    ->sum(function ($order) {
                        return $order->pointDiscount ? $order->pointDiscount->applied_points : 0; 
                    });

                    if ($request->applied_points <= ($user->points - $pending_applied_points)) {
                        OrderPointDiscount::create([
                            'order_id' => $this->frontendOrder->id,
                            'applied_points' => $request->applied_points,
                            'user_id' => $user->id,
                            'point_discount_amount' => $request->point_discount_amount
                        ]);
                    }
                }

            });
            return $this->frontendOrder;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(FrontendOrder $frontendOrder): FrontendOrder|array
    {
        try {
            if ($frontendOrder->user_id == Auth::user()->id) {
                return $frontendOrder;
            }
            return [];
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeStatus(FrontendOrder $frontendOrder, OrderStatusRequest $request): FrontendOrder
    {
        try {
            if ($frontendOrder->user_id == Auth::user()->id) {
                if ($request->status == OrderStatus::CANCELED) {
                    if ($frontendOrder->status >= OrderStatus::ACCEPT) {
                        throw new Exception(trans('all.message.order_accept'), 422);
                    } else {
                        if ($frontendOrder->transaction) {
                            app(PaymentService::class)->cashBack(
                                $frontendOrder,
                                'credit',
                                rand(111111111111111, 99999999999999)
                            );
                        }
                        SendOrderMail::dispatch(['order_id' => $frontendOrder->id, 'status' => $request->status]);
                        SendOrderSms::dispatch(['order_id' => $frontendOrder->id, 'status' => $request->status]);
                        SendOrderPush::dispatch(['order_id' => $frontendOrder->id, 'status' => $request->status]);
                        $frontendOrder->status = $request->status;
                        $frontendOrder->save();

                        SendOrderStatusToWebhook::dispatch(['order_id' => $frontendOrder->id]);
                    }
                }
            }
            return $frontendOrder;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
