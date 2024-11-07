<?php

// app/Http/Controllers/ProjectController.php

namespace App\Http\Controllers;


//
use Inertia\Inertia;

//
use App\Models\Project;

//
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

//
use App\Services\ApiQueryService;

class ProjectController extends Controller
{


    /**
     * 
     */
    public function index()
    {
        //return view('elements.projects.index');
        return Inertia::render('Elements/Projects/Index', [
        ]);
    }


    /**
     * 
     */
    public function create()
    {
        //return view('elements.projects.create');
        $projects = Project::all();
        return Inertia::render('Elements/Projects/Create', [
            'projects' => $projects,
        ]);

    }


    /**
     * 
     */
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //     ]);

    //     Log::channel('debug')->info('Validated ProjectController::store:', $validatedData);

    //     $project = Project::create($validatedData);

    //     Log::channel('debug')->info('Project created successfully', ['name' => $validatedData['name']]);

    //     return response()->json($project, 201);
    // }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        Log::channel('debug')->info('Validated ProjectController::store:', $validatedData);
    
        $project = Project::create($validatedData);
    
        Log::channel('debug')->info('Project created successfully', ['name' => $validatedData['name']]);
    
        // Return an Inertia response with the created project data
        return Inertia::render('Projects/Create', [
            'project' => $project,
            'success' => 'Project created successfully.',
        ]);
    }


    /**
     * 
     */
    public function show(Project $project)
    {
        //return view('elements.projects.show', compact('project'));
        return Inertia::render('Elements/Projects/Show/Page', [
            'project' => $project,
        ]);
    }


    /**
     * 
     */
    public function edit(Project $project)
    {
        return view('elements.projects.edit', compact('project'));
    }



    // Update the specified project in the database
    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validatedData);

        return response()->json($project);
    }

    // Remove the specified project from the database
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }



    /**
     * Handle DataTable data request using raw SQL
     */
    public function getDatatableData(Request $request)
    {
        if ($request->ajax()) {
            // Get the total count of records
            $totalData = DB::table('projects')->count();
            $totalFiltered = $totalData;
    
            // Fetching the data
            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir', 'asc');
            $search = $request->input('search.value');
    
            $columns = [
                0 => 'projects.name',
                // 1 => 'projects.type',
            ];
    
            // Base SQL query
            $sql = "
                SELECT 
                projects.*
                FROM projects
            ";
    
            // Initialize bindings array
            $bindings = [];
    
            // Apply filters
            if ($request->has('filters')) {
                $filters = $request->input('filters');
                $sql .= " WHERE 1=1"; // This allows for chaining multiple conditions
    
                // if (isset($filters['domains.id'])) {
                //     $sql .= " AND domains.id = ?";
                //     $bindings[] = $filters['domains.id'];
                // }
    
                // Add more filters as needed here
            }
    
            // Apply search functionality
            if (!empty($search)) {
                $sql .= !empty($bindings) ? " AND" : " WHERE";
                //$sql .= " (projects.url LIKE ? OR domains.base LIKE ?)";
                $sql .= " (projects.name LIKE ?)";
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
            $projects = DB::select($sql, $bindings);
    
            // Prepare data for DataTables
            $data = [];
            foreach ($projects as $project) {

                //
                $actions = '';
                $actions .= '<div class="btn-group">';
                $actions .= '<a href="'.route('projects.edit', $project->id).'" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-pencil"></i>
                </a>';
                $actions .= '<a href="'.route('projects.view', $project->id).'" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-chevron-right"></i>
                </a>';
                $actions .= '</div>';

                //
                $nestedData = [];
                $nestedData['name'] = $project->name;
                // $nestedData['type'] = $project->type;
                $nestedData['action'] = $actions;
                //
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

    public function apiGet_V1(Request $request)
    {
        // For example, retrieve all projects
        $projects = Project::all();
        return response()->json($projects);
    }
    
    public function apiGet(Request $request, ApiQueryService $apiQueryService)
    {
        // Get search parameters from request
        $searchQuery = $request->get('searchQuery');
        $searchFields = $request->get('searchFields', []);
        $searchOperator = $request->get('searchOperator', 'AND');
    
        // Get filter parameters from request
        $filters = $request->get('filters', []);
    
        // Get order parameters from request
        $orderBy = $request->get('orderBy', 'id');
        $orderDirection = $request->get('orderDirection', 'asc');
    
        // Get pagination parameters from request
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);
    
        // Build the query using the service methods
        $tasksQuery = Project::query();
        $tasksQuery = $apiQueryService->applySearch($tasksQuery, $searchQuery, $searchFields, $searchOperator);
        $tasksQuery = $apiQueryService->applyFilters($tasksQuery, $filters);
        $tasksQuery = $apiQueryService->applyOrder($tasksQuery, $orderBy, $orderDirection);
    
        // Calculate total number of records before applying pagination
        $total = $tasksQuery->count();
    
        // Apply pagination
        $tasksQuery = $apiQueryService->applyPagination($tasksQuery, $limit, $page);
    
        // Log the SQL query
        $sqlQuery = $tasksQuery->toSql();
        $bindings = $tasksQuery->getBindings();
        Log::channel('debug')->info('SQL Query:', ['query' => $sqlQuery, 'bindings' => $bindings]);
    
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
    



}
