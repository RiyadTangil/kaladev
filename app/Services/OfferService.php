<?php

namespace App\Services;


use App\Enums\Status;
use Exception;
use Carbon\Carbon;
use App\Models\Offer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OfferRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;
use App\Models\Branch;

class OfferService
{
    public $offer;
    protected $offerFilter = [
        'name',
        'amount',
        'status',
        'day',
        'start_time',
        'end_time',
    ];

    protected $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';
            $limit       = $request->get('limit') ? $request->get('limit') : '';

            return Offer::with('items')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->offerFilter)) {
                        if ($key == "start_time") {
                            $start_time  = Date('H:i:s', strtotime($request));
                            $query->whereTime($key, '>=', $start_time);
                        } else if ($key == "end_time") {
                            $end_time  = Date('H:i:s', strtotime($request));
                            $query->whereTime($key, '<=', $end_time);
                        } else {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('id', '!=', $explode);
                            }
                        }
                    }
                }
            })->limit($limit)->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function activeWise(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';
            $limit       = $request->get('limit') ? $request->get('limit') : '';

            return Offer::with('items')->where('end_time', '>=', now()->toTimeString())->where(['day' => Carbon::now()->dayOfWeek])->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->offerFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('id', '!=', $explode);
                            }
                        }
                    }
                }
            })->limit($limit)->orderBy($orderColumn, $orderType)->$method(
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
    public function store(OfferRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->offer = Offer::create([
                    'name'       => $request->name,
                    'slug'       => Str::slug($request->name),
                    'amount'     => $request->amount,
                    'day'        => $request->day,
                    'start_time' => $request->start_time,
                    'end_time'   => $request->end_time,
                    'status'     => $request->status,
                ]);
                if ($request->image) {
                    $this->offer->addMedia($request->image)->toMediaCollection('offer');
                }
            });
            return $this->offer;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(OfferRequest $request, Offer $offer)
    {
        try {
            DB::transaction(function () use ($request, $offer) {
                $this->offer       = $offer;
                $offer->name       = $request->name;
                $offer->slug       = Str::slug($request->name);
                $offer->amount     = $request->amount;
                $offer->day        = $request->day;
                $offer->start_time = $request->start_time;
                $offer->end_time   = $request->end_time;
                $offer->status     = $request->status;
                $offer->save();
            });

            if ($request->image) {
                $this->offer->media()->delete();
                $this->offer->addMedia($request->image)->toMediaCollection('offer');
            }
            return $this->offer;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Offer $offer)
    {
        try {
            $offer->offerItems()->delete();
            $offer->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Offer $offer, Branch $branch): Offer
    {
        try {
            $offer->load(['items' => function ($query) use ($branch) {
                $query->withoutGlobalScopes()->where('branch_id', $branch->id);
            }]);
            return $offer;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeImage(ChangeImageRequest $request, Offer $offer): Offer
    {
        try {
            if ($request->image) {
                $offer->clearMediaCollection('offer');
                $offer->addMedia($request->image)->toMediaCollection('offer');
            }
            return $offer;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function offerItemByDate(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return Offer::with('offerItems')->where(['status' => Status::ACTIVE, 'day' => Carbon::now()->dayOfWeek])->get()->filter(function ($offer) {
                if (Carbon::now()->between($offer->start_time, $offer->end_time)) {
                    return $offer;
                }
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
