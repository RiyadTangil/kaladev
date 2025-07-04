<?php

namespace App\Http\Controllers\Table;


use App\Enums\Status;
use App\Http\Resources\ItemCategoryMenuResource;
use App\Models\Branch;
use App\Models\ItemCategory;
use App\Models\DiningTable;
use App\Models\Scopes\BranchScope;
use Exception;
use App\Http\Controllers\Controller;
use App\Services\ItemCategoryService;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ItemCategoryResource;
use Illuminate\Http\Request;


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

    public function index(PaginateRequest $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $itemCategoryArray = [];
            $itemCategories    = $this->itemCategoryService->list($request);
            $allCategory[]     = [
                'id'     => 0,
                'name'   => trans('all.label.all_items'),
                'slug'   => 'all-items',
                'description' => "",
                'status' => Status::ACTIVE,
                'thumb'  => asset("images/default/all-category.png"),
                'cover'  => asset("images/default/all-category.png")
            ];
            foreach ($itemCategories as $itemCategory) {
                $itemCategoryArray[] = [
                    'id'          => $itemCategory->id,
                    'name'        => $itemCategory->name,
                    'slug'        => $itemCategory->slug,
                    'description' => $itemCategory->description === null ? '' : $itemCategory->description,
                    'status'      => $itemCategory->status,
                    'thumb'       => $itemCategory->thumb,
                    'cover'       => $itemCategory->cover
                ];
            }
            return response(['data' => array_merge($allCategory, $itemCategoryArray)]);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(ItemCategory $itemCategory, Request $request)
    {
        try {
            if (isset($request->table_slug)) {
                $table = DiningTable::withoutGlobalScope(BranchScope::class)->where('slug', $request->table_slug)->first();
                if ($table) {
                    $branch = Branch::find($table->branch_id);
                    return new ItemCategoryMenuResource($this->itemCategoryService->categoryWiseItem($itemCategory, $branch));
                }
            }

            return response(['status' => false, 'message' => ''], 422);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}