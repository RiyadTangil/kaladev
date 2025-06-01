<?php

namespace App\Services;


use App\Enums\Ask;
use App\Enums\Status;
use App\Http\Requests\CopyItemRequest;
use App\Models\CopyItem;
use App\Models\Scopes\BranchScope;
use Exception;
use App\Models\Item;
use Illuminate\Support\Str;
use App\Models\ItemVariation;
use App\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;
use Request;

class CopyItemService
{
    public $item;
    protected $itemFilter = [
        'name',
        'slug',
        'item_category_id',
        'price',
        'is_featured',
        'tax_id',
        'status',
        'order',
        'description',
        'branch_id',
        'except'
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

            return Item::withoutGlobalScope(BranchScope::class)->with('media', 'category', 'tax')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->itemFilter)) {
                        if ($key == "except") {
                            $explodes = explode('|', $request);
                            if (count($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('id', '!=', $explode);
                                }
                            }
                        } else {
                            if ($key == "item_category_id" || $key == "branch_id") {
                                $query->where($key, $request);
                            } else {
                                $query->where($key, 'like', '%' . $request . '%');
                            }
                        }
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

    public function copyItems(CopyItemRequest $request)
    {
        try {

            $items = [];
            if($request->is_checked_all){
                $items = Item::withoutGlobalScope(BranchScope::class)->whereNotIn('id', $request->excepts)->pluck('id');
            }else{
                $items = $request->items;
            }

            if (count($items) > 0) {

                DB::transaction(function () use ($request, $items) {
                    foreach ($items as $itemId) {
                        $oldItem = Item::withoutGlobalScope(BranchScope::class)->with(['media', 'variations', 'extras'])->find($itemId);

                        if (!$oldItem) {
                            continue;
                        }

                        $exists = Item::where('name', $oldItem->name)
                            ->where('branch_id', $request->branch_to)
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        // item info
                        $newItem = Item::withoutGlobalScope(BranchScope::class)->create(array_merge(
                            $oldItem->toArray(),
                            ['branch_id' => $request->branch_to, 'slug' => Str::slug($oldItem->name) . '-' . $request->branch_to]
                        ));

                        // media
                        foreach ($oldItem->media as $media) {
                            $media->copy($newItem, 'item');
                        }

                        // variations
                        foreach ($oldItem->variations as $variation) {
                            $newItem->variations()->create($variation->toArray());
                        }

                        // extras
                        foreach ($oldItem->extras as $extra) {
                            $newItem->extras()->create($extra->toArray());
                        }
                    }
                });

                return;
            }

            throw new Exception(trans('all.message.something_wrong'), 422);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }
}