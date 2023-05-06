<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresenceRegulationRequest;
use App\Http\Resources\PresenceRegulationResource;
use App\Models\PresenceRegulation;
use Illuminate\Http\Request;

class PresenceRegulationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $presenceRegulations = PresenceRegulation::with('employee')->get();
       return $presenceRegulations;
    }

    /**
     * Show the form for creating a new resource.
     */
   

    /**
     * Store a newly created resource in storage.
     */
   
    public function updateStatusToApproved(Request $request, $id)
{
    $regulation = PresenceRegulation::findOrFail($id);
    $regulation->status = "Approved";
    $regulation->save();
    return response()->json([
        "message" => "Regulation Approved  successfully",
        "data" => $regulation
    ], 200);
}

public function updateStatusToRejected(Request $request, $id)
{
    $regulation = PresenceRegulation::findOrFail($id);
    $regulation->status = "Rejected";
    $regulation->save();
    return response()->json([
        "message" => "Regulation Rejected successfully",
        "data" => $regulation
    ], 200);
}

    public function getRegulation($id)
    {
        $regulation = PresenceRegulation::with('employee')->findOrFail($id);
        if (!$regulation) {
            return response()->json(['error' => 'admin not found.'], 404);
        }
    
        return response()->json($regulation);
    }
}
