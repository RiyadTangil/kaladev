<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RiderTipRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\RiderTipResource;
use App\Models\RiderTip;
use App\Services\RiderTipService;
use Exception;

class RiderTipController extends AdminController
{
    private RiderTipService $riderTipService;

    public function __construct(RiderTipService $riderTipService)
    {
        parent::__construct();
        $this->riderTipService = $riderTipService;
        $this->middleware(['permission:settings'])->only('index', 'store', 'update', 'destroy');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return RiderTipResource::collection($this->riderTipService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(RiderTipRequest $request): RiderTipResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new RiderTipResource($this->riderTipService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(RiderTipRequest $request, RiderTip $riderTip): RiderTipResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new RiderTipResource($this->riderTipService->update($request, $riderTip));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(RiderTip $riderTip): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->riderTipService->destroy($riderTip);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
