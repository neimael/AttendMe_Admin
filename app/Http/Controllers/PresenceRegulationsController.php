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
       $presenceRegulations = PresenceRegulation::all();
       return PresenceRegulationResource::collection($presenceRegulations) ;
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
    public function store(StorePresenceRegulationRequest $request)
    {
        $presenceRegulation=new PresenceRegulation();
        $presenceRegulation->id_employee=$request->id_employee;
        $presenceRegulation->check_in=$request->check_in;
        $presenceRegulation->check_out=$request->check_out;
        $presenceRegulation->status=$request->status;
        $presenceRegulation->attendance_day=$request->attendance_day;
        $presenceRegulation->issue_type=$request->issue_type;
        $presenceRegulation->save();
        return new PresenceRegulationResource($presenceRegulation);



    }

    /**
     * Display the specified resource.
     */
    public function show(PresenceRegulation $presenceRegulation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresenceRegulation $presenceRegulation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresenceRegulation $presenceRegulation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresenceRegulation $presenceRegulation)
    {
        //
    }
}
