<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CopyItemRequest;
use Exception;
use App\Services\CopyItemService;
use App\Http\Resources\ItemResource;
use App\Http\Requests\PaginateRequest;

class CopyItemController extends AdminController
{
    protected $apiRequest;
    public CopyItemService $copyItemService;

    public function __construct(CopyItemService $copyItemService)
    {
        parent::__construct();
        $this->copyItemService = $copyItemService;
        $this->middleware(['permission:copy_items'])->only('index', 'copyItems');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemResource::collection($this->copyItemService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function copyItems(CopyItemRequest $request)
    {
        try {
            return $this->copyItemService->copyItems($request);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
