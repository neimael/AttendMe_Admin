<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsignmentElevator;
use App\Models\AssignmentElevator;
use Illuminate\Http\Request;
use App\Http\Resources\AssignmentElevatorResource;

class AssignmentElevatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $assignmentElevator=AssignmentElevator::with(['employee','qrcode.elevator.location'])->get();
        return $assignmentElevator;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAsignmentElevator $request)
    {
            
            $assignmentElevator=new AssignmentElevator();
            $assignmentElevator->id_employee=$request->id_employee;
            $assignmentElevator->id_elevator=$request->id_elevator;
            $assignmentElevator->start_date=$request->start_date;
            $assignmentElevator->end_date=$request->end_date;
            $assignmentElevator->time_in=$request->time_in;
            $assignmentElevator->time_out=$request->time_out;
            $assignmentElevator->save();
            return new AssignmentElevatorResource($assignmentElevator);
    }

    /**
     * Display the specified resource.
     */
    public function show(AssignmentElevator $assignmentElevator)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssignmentElevator $assignmentElevator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssignmentElevator $assignmentElevator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $assignmentElevator=AssignmentElevator::find($id);
      

    if (!$assignmentElevator) {
        return response()->json(['message' => 'Asignment not found'], 404);
    }

    $assignmentElevator->delete();

    return response()->json(['message' => 'Asignment has been deleted successfully']);

    }
}
