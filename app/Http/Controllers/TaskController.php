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
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id', 
            'parent_task_id' => [
                'nullable',
                'exists:tasks,id',
                Rule::notIn([$task->id]), // Exclude the current task's id
            ],
        ]);
    
        Log::channel('debug')->info('Validated taskController::update:', $validatedData);
    
        $task->update($validatedData);
    
        Log::channel('debug')->info('Task updated successfully', ['name' => $validatedData['name']]);
    
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


    /*
    * API
    * =================================================================
    */ 

    public function apiGetMultiple(Request $request, ApiQueryService $apiQueryService)
    {
        // Get search parameters from request
        $searchQuery = $request->get('searchQuery');
        $searchFields = $request->get('searchFields', []);
        $searchOperator = $request->get('searchOperator', 'AND');
        $primaryTable = 'tasks';
    
        // Get filter parameters from request
        $filters = $request->get('filters', []);
    
        // Get IDs to exclude, ensure it's an array
        $filterExcludeIds = (array) $request->get('filterExcludeIds', []);
    
        // Get order parameters from request
        $orderBy = $request->get('orderBy', "{$primaryTable}.id");
        $orderDirection = $request->get('orderDirection', 'asc');
    
        // Get pagination parameters from request
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);
    
        // Build the query using the service methods
        $tasksQuery = Task::query()
            ->select('tasks.*', 'projects.id as project_id', 'projects.name as project_name')
            ->leftJoin('projects', 'tasks.project_id', '=', 'projects.id');
    
        // Log the SQL query
        $sqlQuery = $tasksQuery->toSql();
        $bindings = $tasksQuery->getBindings();
        Log::channel('debug')->info('SQL Query before pagination:', ['query' => $sqlQuery, 'bindings' => $bindings]);
    
        $tasksQuery = $apiQueryService->applySearch($tasksQuery, $searchQuery, $searchFields, $searchOperator);
        $tasksQuery = $apiQueryService->applyFilters($tasksQuery, $filters, $primaryTable);
    
        // Exclude specific IDs
        if (!empty($filterExcludeIds)) {
            Log::channel('debug')->info('Excluding task IDs:', ['filterExcludeIds' => $filterExcludeIds]);
            $tasksQuery->whereNotIn("{$primaryTable}.id", $filterExcludeIds); // Use "{$primaryTable}.id" for clarity
        }
    
        $tasksQuery = $apiQueryService->applyOrder($tasksQuery, $orderBy, $orderDirection);
    
        // Calculate total number of records before applying pagination
        $total = $tasksQuery->count();
    
        // Apply pagination
        $tasksQuery = $apiQueryService->applyPagination($tasksQuery, $limit, $page);
    
        // Log the SQL query
        $sqlQuery = $tasksQuery->toSql();
        $bindings = $tasksQuery->getBindings();
        Log::channel('debug')->info('SQL Query after pagination:', ['query' => $sqlQuery, 'bindings' => $bindings]);
    
        // Execute the query and return results
        $tasks = $tasksQuery->get();
    
        // Return response with additional pagination information
        return response()->json([
            'items' => $tasks,
            'total' => $total,  // total number of records
            'limit' => $limit,
            'page' => $page,
        ]);
    }
    

    // Method for fetching a single task
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
