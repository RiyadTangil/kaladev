<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use App\Services\FrontendTimeSlotService;
use App\Http\Resources\FrontendTimeSlotResource;
use App\Models\Branch;
use Exception;

class TimeSlotController extends Controller
{
    public FrontendTimeSlotService $frontendTimeSlotService;

    public function __construct(FrontendTimeSlotService $frontendTimeSlotService)
    {
        $this->frontendTimeSlotService = $frontendTimeSlotService;
    }

    public function todayTimeSlot(Branch $branch): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return FrontendTimeSlotResource::collection($this->frontendTimeSlotService->todayTimeSlot($branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function tomorrowTimeSlot(Branch $branch): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return FrontendTimeSlotResource::collection($this->frontendTimeSlotService->tomorrowTimeSlot($branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
