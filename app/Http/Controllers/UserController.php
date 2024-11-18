<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

//
use Inertia\Inertia;

//
use App\Models\User;
use App\Models\Project;

//
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

//
use App\Services\ApiQueryService;


class UserController extends Controller
{

    public function __construct(ApiQueryService $apiQueryService)
    {
        $this->apiQueryService = $apiQueryService;
    }


    /**
     * 
     */
    public function index()
    {

        return Inertia::render('Elements/Users/Index', [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    /*
    * API
    * =================================================================
    */ 


    /**
     * Main API Method
     */
    public function apiGetMultiple(Request $request, ApiQueryService $apiQueryService)
    {
        // Get search parameters from request
        $searchQuery = $request->get('searchQuery');
        $searchFields = $request->get('searchFields', []);
        $searchOperator = $request->get('searchOperator', 'AND');
        $primaryTable = 'users';

        // Get filter parameters from request, excluding `includeSubtasks`
        $filters = $request->get('filters', []);
        //unset($filters['includeSubtasks']); // Remove `includeSubtasks` from filters

        // Get order parameters from request
        $orderBy = $request->get('orderBy', "{$primaryTable}.id");
        $orderDirection = $request->get('orderDirection', 'asc');

        // Get pagination parameters from request
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        // Build the base query
        $userQuery = User::query()
            ->select(
                'users.*',
                // 'projects.id as project_id',
                // 'projects.name as project_name',
                // 'parent_tasks.id as parent_task_id',
                // 'parent_tasks.name as parent_task_name'
            )
            // ->leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
            // ->leftJoin('tasks as parent_tasks', 'tasks.parent_task_id', '=', 'parent_tasks.id')
            ;

        // Apply filters and modifications
        //$this->applyFilters($request, $userQuery);

        // Apply search and filters using the service
        $userQuery = $apiQueryService->applySearch($userQuery, $searchQuery, $searchFields, $searchOperator);
        //$userQuery = $apiQueryService->applyFilters($userQuery, $filters, $primaryTable);

        // Apply ordering
        $userQuery = $apiQueryService->applyOrder($userQuery, $orderBy, $orderDirection);

        // Calculate total number of records before pagination
        $total = $userQuery->count();

        // Apply pagination
        $userQuery = $apiQueryService->applyPagination($userQuery, $limit, $page);

        // Execute the query and return results
        $tasks = $userQuery->get();

        // Return response with additional pagination information
        return response()->json([
            'items' => $tasks,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
        ]);
    }



}
