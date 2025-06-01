<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Branch;
use App\Models\DeliverySetup;
use App\Http\Requests\PaginateRequest;
use App\Services\DeliverySetupService;
use App\Http\Requests\DeliverySetupRequest;
use App\Http\Resources\DeliverySetupResource;

class DeliverySetupController extends AdminController
{
    public DeliverySetupService $deliverySetupService;

    public function __construct(DeliverySetupService $deliverySetupService)
    {
        parent::__construct();
        $this->deliverySetupService = $deliverySetupService;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy');
    }

    public function index(PaginateRequest $request, Branch $branch): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return DeliverySetupResource::collection($this->deliverySetupService->list($request, $branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function store(DeliverySetupRequest $request, Branch $branch): \Illuminate\Http\Response | DeliverySetupResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new DeliverySetupResource($this->deliverySetupService->store($request, $branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function update(DeliverySetupRequest $request, Branch $branch, DeliverySetup $deliverySetup): \Illuminate\Http\Response | DeliverySetupResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new DeliverySetupResource($this->deliverySetupService->update($request, $branch, $deliverySetup));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function destroy(Branch $branch, DeliverySetup $deliverySetup): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->deliverySetupService->destroy($branch, $deliverySetup);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}