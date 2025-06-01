<?php

namespace App\Http\Controllers\Frontend;


use Exception;
use Illuminate\Http\Request;
use App\Services\ItemService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\NormalItemResource;
use App\Models\Branch;

class ItemController extends Controller
{

    public ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(
        PaginateRequest $request
    ): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return NormalItemResource::collection($this->itemService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function featuredItems(
        Request $request
    ): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {

            $branch = Branch::findOrFail($request->branch_id);
            return NormalItemResource::collection($this->itemService->featuredItems($branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function mostPopularItems(Request $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $branch = Branch::findOrFail($request->branch_id);
            return NormalItemResource::collection($this->itemService->mostPopularItems($branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}