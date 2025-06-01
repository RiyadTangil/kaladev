<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Services\DeliverySetupService;
use App\Http\Resources\DeliverySetupResource;

class DeliverySetupController extends Controller
{
    public DeliverySetupService $deliverySetupService;

    public function __construct(DeliverySetupService $deliverySetupService)
    {
        $this->deliverySetupService = $deliverySetupService;
    }

    public function index(PaginateRequest $request, Branch $branch): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return DeliverySetupResource::collection($this->deliverySetupService->list($request, $branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function distanceCheck(Branch $branch, Request $request)
    {
        try {
            $deliverySetup = $this->deliverySetupService->distanceCheck($branch, $request);
            if ($deliverySetup) {
                return new DeliverySetupResource($deliverySetup);
            } else {
                return ['data' => (object)[]];
            }
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
