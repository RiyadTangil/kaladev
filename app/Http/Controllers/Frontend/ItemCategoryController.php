<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Resources\ItemCategoryMenuResource;
use App\Models\ItemCategory;
use Exception;
use App\Http\Controllers\Controller;
use App\Services\ItemCategoryService;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ItemCategoryResource;
use App\Models\Branch;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private ItemCategoryService $itemCategoryService;

    public function __construct(ItemCategoryService $itemCategory)
    {
        $this->itemCategoryService = $itemCategory;
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemCategoryResource::collection($this->itemCategoryService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(ItemCategory $itemCategory, Branch $branch): \Illuminate\Http\Response | ItemCategoryMenuResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new ItemCategoryMenuResource($this->itemCategoryService->categoryWiseItem($itemCategory, $branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}