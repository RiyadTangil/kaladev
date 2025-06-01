<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Services\RiderTipService;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\RiderTipResource;
use App\Http\Controllers\Controller;

class RiderTipController extends Controller
{
    public RiderTipService $riderTipService;

    public function __construct(RiderTipService $riderTipService)
    {
        $this->riderTipService = $riderTipService;
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return RiderTipResource::collection($this->riderTipService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
