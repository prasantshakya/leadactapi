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

        
public function getallleads(Request $request)
{
    try {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => '0',
                'message' => 'Token expired or user not authenticated'
            ], 401); // Using 401 for Unauthorized errors
        }

        // Get the authenticated user's company ID and user ID
        $company_id = auth()->user()->created_by;
        $user_id = auth()->user()->id;

        // Build the query based on filters
        $query = Lead::where('created_by', $company_id);

        // Apply filters if available in the request
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('source_id')) {
            $query->where('sources', $request->source_id);
        }
        

        if ($request->filled('substatus_id')) {
            $query->where('substatus_id', $request->substatus_id);
        }

        // Fetch the data from the database
        $data = $query->orderBy('created_at', 'desc')->get();

        // Map the data as per the required format
        $leaddata = $data->map(function ($lead) {
            // Extract the last 10 digits of the mobile number and prefix it with +91
            $formattedMobile = $lead->phone_no ? '+91' . substr($lead->phone_no, -10) : null;

            return [
                'id' => $lead->id,
                'name' => $lead->name,
                'email' => $lead->email,
                'mobile' => $formattedMobile,
                'city' => $lead->location,
                'substatus_name' => $lead->subStatus->name ?? null,
                'status_name' => $lead->status->name ?? null,
                'source' => $lead->source->name ?? null,
                'created_date' => $lead->created_at->format('Y-m-d H:i:s') // Format date as needed
            ];
        });

        // Return the mapped data
        return response()->json([
            'success' => '1',
            'message' => 'success',
            'data' => $leaddata->toArray()
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => '0',
            'message' => $e->getMessage()
        ], 500);
    }
}

      
    
}
