<?php

// app/Http/Controllers/TaskController.php

namespace App\Http\Controllers;

//
use Inertia\Inertia;

//
use App\Models\Task;
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


class TaskController extends Controller
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

        return Inertia::render('Elements/Tasks/Index', [
        ]);
    }


    /**
     * 
     */
    public function create()
    {
        $projects = Project::all();
        return Inertia::render('Elements/Tasks/Create', [
            'projects' => $projects,
        ]);
    }

    /**
     * 
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'parent_task_id' => [
                'nullable',
                'exists:tasks,id',
            ],
        ]);
    
        Log::channel('debug')->info('Validated taskController::store:', $validatedData);
    
        $task = Task::create($validatedData);
    
        Log::channel('debug')->info('Task created successfully', ['name' => $validatedData['name']]);
    

        // // Define response structure
        // $response = [
        //     'data' => $task,  // The created task object
        //     'redirect' => route('tasks.index'), // Set the path or `false` if no redirection is needed
        //     'messages' => [
        //         [
        //             'type' => 'success',
        //             'text' => 'Task created successfully!',
        //             'render' => 'toast',
        //         ]
        //     ],
        // ];

        // //return response()->json($response, 201);

        // return Inertia::render('Tasks/Store', $response);
        return back()->with([
            'messages' => [
                [
                    'type' => 'success',
                    'text' => 'Task created successfully!',
                    'render' => 'toast',
                ]
            ]
        ]);


    }


    /**
     * 
     */
    public function show(Task $task)
    {
        return Inertia::render('Elements/Tasks/Show/Page', [
            'task' => $task,
        ]);
    }

    /**
     * 
     */
    public function edit($id)
    {
        // Find the task by ID and eager load both the project and the parent task relationships
        $task = Task::with(['project', 'parentTask'])->findOrFail($id);

        return view('elements.tasks.edit', compact('task'));
    }



    // Update the specified task in the database
    // public function update(Request $request, Task $task)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'project_id' => 'required|exists:projects,id', 
    //         'parent_task_id' => [
    //             'nullable',
    //             'exists:tasks,id',
    //             Rule::notIn([$task->id]), // Exclude the current task's id
    //         ],
    //     ]);
    
    //     Log::channel('debug')->info('Validated taskController::update:', $validatedData);
    
    //     $task->update($validatedData);
    
    //     Log::channel('debug')->info('Task updated successfully', ['name' => $validatedData['name']]);
    
    //     return response()->json($task);
    // }
    

    public function update(Request $request, Task $task)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'content' => 'nullable|string', // Add this line to validate the content field
        'project_id' => 'required|exists:projects,id',
        'parent_task_id' => [
            'nullable',
            'exists:tasks,id',
            Rule::notIn([$task->id]),
        ],
    ]);

    $task->update($validatedData); // Update the task with the new data

    return response()->json($task);
}

    // Remove the specified task from the database
    public function destroy(task $task)
    {
        $task->delete();

        return response()->json(['message' => 'task deleted successfully']);
    }


    /**
     * Handle DataTable data request using raw SQL
     */
    public function getDatatableData(Request $request)
    {
        if ($request->ajax()) {
            // Get the total count of records
            $totalData = DB::table('tasks')->count();
            $totalFiltered = $totalData;
    
            // Fetching the data
            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir', 'asc');
            $search = $request->input('search.value');
    
            $columns = [
                0 => 'tasks.name',
                1 => 'projects.name',
            ];
    
            // Base SQL query with join to projects
            $sql = "
                SELECT 
                    tasks.*,
                    projects.name as project_name
                FROM tasks
                LEFT JOIN projects ON tasks.project_id = projects.id
            ";
    
            // Initialize bindings array
            $bindings = [];
    
            // Apply filters
            if ($request->has('filters')) {
                $filters = $request->input('filters');
                $sql .= " WHERE 1=1"; // This allows for chaining multiple conditions
    
                // Add more filters as needed here
            }
    
            // Apply search functionality
            if (!empty($search)) {
                $sql .= !empty($bindings) ? " AND" : " WHERE";
                $sql .= " (tasks.name LIKE ? OR projects.name LIKE ?)";
                $bindings[] = "%{$search}%";
                $bindings[] = "%{$search}%";
            }
    
            // Count the total filtered records
            $countSql = "SELECT COUNT(*) as count FROM (" . $sql . ") as filtered";
            $totalFilteredResult = DB::selectOne($countSql, $bindings);
            $totalFiltered = $totalFilteredResult ? $totalFilteredResult->count : 0;
    
            // Append ordering, limit, and offset
            $sql .= " ORDER BY " . $columns[$orderColumnIndex] . " " . $orderDir;
            $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($start);
    
            // Fetch the data
            $tasks = DB::select($sql, $bindings);
    
            // Prepare data for DataTables
            $data = [];
            foreach ($tasks as $task) {

                //
                $actions = '';
                $actions .= '<div class="btn-group">';
                $actions .= '<a href="'.route('tasks.edit', $task->id).'" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                            </a>';
                $actions .= '<a href="'.route('tasks.view', $task->id).'" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chevron-right"></i>
                            </a>';
                $actions .= '</div>';

                $subTasks = // LIVEWIRE;

                //
                $fieldCompletedCheckmark = '<i class="bi bi-check-circle fs-4"></i>';
    
                $nestedData = [];
                $nestedData['completed'] = $fieldCompletedCheckmark;
                $nestedData['name'] = $task->name;
                $nestedData['project_name'] = $task->project_name; // Adding the project name to the data array
                $nestedData['sub_tasks'] = $subTasks;
                $nestedData['action'] = $actions;
    
                $data[] = $nestedData;
            }
    
            // Prepare the response data
            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];
    
            return response()->json($json_data);
        }
    
        return abort(404);
    }


    /**
     * Main Method to Apply All Filters
     */

    private function applyFilters(Request $request, $tasksQuery)
    {
        $filters = $request->get('filters', []);

        // Apply each filter using the central filters array
        $this->applyIncludeSubtasksFilter($filters, $tasksQuery);
        $this->applyFilterExcludeIds($filters, $tasksQuery);
        $this->applyFilterByProjectId($filters, $tasksQuery);
        $this->applyParentTaskIdFilter($filters, $tasksQuery); // Add this line
    }


    /**
     * Method to Handle `includeSubtasks` Filter
     */
    private function applyIncludeSubtasksFilter(array $filters, $tasksQuery)
    {
        $includeSubtasks = $filters['includeSubtasks'] ?? false;
        $parentTaskId = $filters['parent_task_id'] ?? null;
    
        // If we're filtering by a specific parent_task_id, we want to show those children
        // regardless of the includeSubtasks setting
        if ($parentTaskId) {
            $tasksQuery->where('tasks.parent_task_id', $parentTaskId);
        } else {
            // Only apply the includeSubtasks filter when we're not filtering by parent_task_id
            if (!$includeSubtasks || $includeSubtasks === 'false') {
                $tasksQuery->whereNull('tasks.parent_task_id');
            }
        }
    }


    /**
     * Method to Handle Excluding Specific IDs
     */
    private function applyFilterExcludeIds(array $filters, $tasksQuery)
    {
        $filterExcludeIds = (array) ($filters['filterExcludeIds'] ?? []);
        if (!empty($filterExcludeIds)) {
            $tasksQuery->whereNotIn('tasks.id', $filterExcludeIds);
        }
    }


    /**
     * Method to Handle Filtering by `project_id`
     */
    private function applyFilterByProjectId(array $filters, $tasksQuery)
    {
        $projectId = $filters['project_id'] ?? null;
        if (!empty($projectId)) {
            $tasksQuery->where('tasks.project_id', $projectId);
        }
    }

    /**
     * Method to Handle `parent_task_id` Filter
     */
    private function applyParentTaskIdFilter(array $filters, $tasksQuery)
    {
        if (isset($filters['parent_task_id'])) {
            $parentTaskId = $filters['parent_task_id'];

            // Apply the filter to get tasks with the specified `parent_task_id`
            $tasksQuery->where('tasks.parent_task_id', '=', $parentTaskId);
        }
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
        $primaryTable = 'tasks';

        // Get filter parameters from request, excluding `includeSubtasks`
        $filters = $request->get('filters', []);
        unset($filters['includeSubtasks']); // Remove `includeSubtasks` from filters

        // Get order parameters from request
        $orderBy = $request->get('orderBy', "{$primaryTable}.id");
        $orderDirection = $request->get('orderDirection', 'asc');

        // Get pagination parameters from request
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        // Build the base query
        $tasksQuery = Task::query()
            ->select(
                'tasks.*',
                'projects.id as project_id',
                'projects.name as project_name',
                'parent_tasks.id as parent_task_id',
                'parent_tasks.name as parent_task_name'
            )
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
            ->leftJoin('tasks as parent_tasks', 'tasks.parent_task_id', '=', 'parent_tasks.id');

        // Apply filters and modifications
        $this->applyFilters($request, $tasksQuery);

        // Apply search and filters using the service
        $tasksQuery = $apiQueryService->applySearch($tasksQuery, $searchQuery, $searchFields, $searchOperator);
        $tasksQuery = $apiQueryService->applyFilters($tasksQuery, $filters, $primaryTable);

        // Apply ordering
        $tasksQuery = $apiQueryService->applyOrder($tasksQuery, $orderBy, $orderDirection);

        // Calculate total number of records before pagination
        $total = $tasksQuery->count();

        // Apply pagination
        $tasksQuery = $apiQueryService->applyPagination($tasksQuery, $limit, $page);

        // Execute the query and return results
        $tasks = $tasksQuery->get();

        // Return response with additional pagination information
        return response()->json([
            'items' => $tasks,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
        ]);
    }


    /**
     * Method for fetching a single task
     */
    public function apiGetSingle($id)
    {
        // Validate that an ID is provided (although not strictly necessary since it's part of the route)
        if (!$id) {
            return response()->json(['message' => 'Task ID is required'], 400);
        }
    
        // Fetch the task by ID
        $task = Task::query()
            ->select('tasks.*', 'projects.id as project_id', 'projects.name as project_name')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.id', $id)
            ->first();
    
        // Handle task not found
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
    
        return response()->json(['item' => $task]);
    }
    


}
