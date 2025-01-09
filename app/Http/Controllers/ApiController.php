<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;

class ApiController extends Controller
{
    /**
     * Retrieve the authenticated user's profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
 
    
    
    public function userProfile()
{
    // Authenticate the user using the current authenticated session
    $user = auth()->user();
    
    // Check if the user is authenticated
    if ($user) {
        return response()->json([
            'success' => 1,
            'message' => 'Success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
            ]
        ]);
    } else {
        // Return Unauthorized status if the token is expired or invalid
        return response()->json([
            'success' => 0,
            'message' => 'Unauthorized. Please login again.',
        ], 401);  // 401 is the appropriate status code for authentication failure
    }
}

    public function getAllLeads()
{
    // Get the authenticated user's ID
    $userId = auth()->id();

    // Check if the user is authenticated
    if (!$userId) {
        return response()->json([
            'success' => 0,
            'message' => 'Unauthorized. Please login again.',
        ], 401); // Return 401 Unauthorized if no user is authenticated
    }

    // Fetch the leads associated with the authenticated user's ID
    $leads = Lead::where('user_id', $userId)->get();

    // Check if any leads are found
    if ($leads->isEmpty()) {
        return response()->json([
            'success' => 0,
            'message' => 'No leads found.',
        ], 404); // Return 404 if no leads are found
    }

    // Return the leads data
    return response()->json([
        'success' => 1,
        'message' => 'Leads fetched successfully.',
        'data' => $leads,
    ]);
}

    
}
