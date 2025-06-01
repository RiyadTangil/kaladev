<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\TimeSlot;
use App\Services\TimeSlotService;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\TimeSlotRequest;
use App\Http\Resources\TimeSlotResource;
use App\Models\Branch;

class TimeSlotController extends AdminController
{
    public TimeSlotService $timeSlotService;

    public function __construct(TimeSlotService $timeSlotService)
    {
        parent::__construct();
        $this->timeSlotService = $timeSlotService;
        $this->middleware(['permission:settings'])->only('store', 'destroy');
    }

    public function index(PaginateRequest $request, Branch $branch)
    {
        try {
            return TimeSlotResource::collection($this->timeSlotService->list($request, $branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(TimeSlotRequest $request, Branch $branch)
    {
        try {
            return new TimeSlotResource($this->timeSlotService->store($request, $branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Branch $branch, TimeSlot $timeSlot)
    {
        try {
            $this->timeSlotService->destroy($branch, $timeSlot);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
