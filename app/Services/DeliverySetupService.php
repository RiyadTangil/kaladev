<?php

namespace App\Services;


use Exception;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\DeliverySetup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\DeliverySetupRequest;

class DeliverySetupService
{
    public $deliverySetup;
    protected $deliverySetupFilter = [
        'branch_id',
        'kilometer_range',
        'minimum_order_amount',
        'delivery_charge'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request, Branch $branch)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return DeliverySetup::with('branch')->where(['branch_id' => $branch->id])->where(
                function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->deliverySetupFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
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
    public function store(DeliverySetupRequest $request, Branch $branch)
    {
        try {
            return DeliverySetup::create($request->validated() + ['branch_id' => $branch->id]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(DeliverySetupRequest $request, Branch $branch, DeliverySetup $deliverySetup): DeliverySetup
    {
        try {
            if ($branch->id == $deliverySetup->branch_id) {
                return tap($deliverySetup)->update($request->validated());
            }
            return $deliverySetup;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function destroy(Branch $branch, DeliverySetup $deliverySetup): void
    {
        try {
            if ($branch->id == $deliverySetup->branch_id) {
                $deliverySetup->delete();
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function distanceCheck(Branch $branch, Request $request)
    {
        try {
            $userLatitude = $request->latitude;
            $userLongitude = $request->longitude;
            $zoneData = json_decode(json_decode($branch->zone, true), true);
            if ($zoneData && $request->latitude && $request->longitude) {
                if ($this->isPointInPolygon($zoneData, $userLatitude, $userLongitude)) {
                    if ($request->distance) {
                        $maxKilometerRange = DeliverySetup::where('branch_id', $branch->id)->max('kilometer_range');
                        if ($request->distance <= $maxKilometerRange) {
                            return DeliverySetup::where('branch_id', $branch->id)
                                ->orderByRaw('ABS(kilometer_range - ?)', [$request->distance])
                                ->first();
                        }
                    }
                } else {
                    throw new Exception(trans('all.message.out_of_service_area'), 422);
                }
            } else {
                throw new Exception(trans('all.message.out_of_service_area'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    function isPointInPolygon($polygon, $latitude, $longitude)
    {
        $intersections = 0;
        $verticesCount = count($polygon);

        for ($i = 1; $i < $verticesCount; $i++) {
            $vertex1 = $polygon[$i - 1];
            $vertex2 = $polygon[$i];

            if ($vertex1['lng'] == $vertex2['lng'] && $longitude == $vertex1['lng']) {
                return true;
            }

            if (
                $longitude > min($vertex1['lng'], $vertex2['lng']) &&
                $longitude <= max($vertex1['lng'], $vertex2['lng']) &&
                $latitude <= max($vertex1['lat'], $vertex2['lat']) &&
                $vertex1['lng'] != $vertex2['lng']
            ) {
                $xinters = ($longitude - $vertex1['lng']) * ($vertex2['lat'] - $vertex1['lat']) / ($vertex2['lng'] - $vertex1['lng']) + $vertex1['lat'];

                if ($vertex1['lat'] == $vertex2['lat'] || $latitude <= $xinters) {
                    $intersections++;
                }
            }
        }
        return $intersections % 2 != 0;
    }
}
