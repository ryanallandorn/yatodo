<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ApiQueryService;


class UserController extends Controller
{


    protected $apiQueryService;

    public function __construct(ApiQueryService $apiQueryService)
    {
        $this->apiQueryService = $apiQueryService;
    }

    public function apiGet(Request $request)
    {
        // Build the query for the Task model
        $query = User::query();

        // Extract request parameters
        $params = $request->all();

        // Use the service to get the filtered results
        $response = $this->apiQueryService->getFilteredResults($query, $params);

        return response()->json($response);
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

}
