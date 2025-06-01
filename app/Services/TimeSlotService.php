<?php

namespace App\Services;


use Exception;
use App\Models\Branch;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\TimeSlotRequest;

class TimeSlotService
{

    /**
     * @throws Exception
     */
    public $timeSlotFilter = ['opening_time', 'closing_time', 'day'];


    public function list(PaginateRequest $request, Branch $branch)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_type') ?? 'desc';

            return TimeSlot::where(['branch_id' => $branch->id])->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->timeSlotFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
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
    public function store(TimeSlotRequest $request, Branch $branch)
    {
        try {
            $status = true;
            $timeSlots = TimeSlot::where(['day' => $request->day, 'branch_id' => $branch->id])->get();
            foreach ($timeSlots as $timeSlot) {
                if ($request->opening_time > $timeSlot->opening_time && $request->closing_time < $timeSlot->closing_time) {
                    $status = false;
                } elseif ($request->opening_time > $timeSlot->opening_time && $request->opening_time < $timeSlot->closing_time) {
                    $status = false;
                } elseif ($request->closing_time > $timeSlot->opening_time && $request->closing_time < $timeSlot->closing_time) {
                    $status = false;
                }
            }

            if ($status) {
                return TimeSlot::create($request->validated() + ['branch_id' => $branch->id]);
            } else {
                throw new Exception(trans('all.message.time_slot_exist'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Branch $branch, TimeSlot $timeSlot): void
    {
        try {
            if ($branch->id == $timeSlot->branch_id) {
                $timeSlot->delete();
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
