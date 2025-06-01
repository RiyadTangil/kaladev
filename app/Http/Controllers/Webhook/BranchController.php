<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Resources\WebhookOrderResource;
use Exception;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Services\WebhookService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\BranchResource;

class BranchController extends Controller
{
    public WebhookService $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return BranchResource::collection($this->webhookService->branches());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Branch $branch): BranchResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new BranchResource($this->webhookService->branch($branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function orders(Branch $branch, Request $request)
    {
        try {
            return WebhookOrderResource::collection($this->webhookService->branchOrders($branch,$request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}