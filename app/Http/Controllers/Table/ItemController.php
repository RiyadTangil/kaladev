<?php

namespace App\Http\Controllers\Table;



use App\Models\Branch;
use App\Models\Scopes\BranchScope;
use Exception;
use Illuminate\Http\Request;
use App\Services\TableItemService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\NormalItemResource;
use App\Models\DiningTable;

class ItemController extends Controller
{

    public TableItemService $tableItemService;

    public function __construct(TableItemService $tableItemService)
    {
        $this->tableItemService = $tableItemService;
    }

    public function index($table_slug,
        PaginateRequest $request
    ) {
        try {

            if(isset($table_slug)){
                $diningTable = DiningTable::withoutGlobalScope(BranchScope::class)->where('slug',$table_slug)->first();
                if($diningTable){
                    return NormalItemResource::collection($this->tableItemService->list($request, $diningTable));
                }
            }

            return response(['status' => false, 'message' => ''], 422);

        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}