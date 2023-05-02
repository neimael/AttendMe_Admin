<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreElevator;
use App\Http\Resources\ElevatorResource;
use App\Models\Elevator;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;

class ElevatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $elevators = Elevator::with('location','qrcode')->get();
        //return ElevatorResource::collection($elevators);
        return $elevators;
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
    public function store(StoreElevator $request)
    {
            
            $elevator=new Elevator();
            $elevator->name=$request->name;
            $elevator->qr_code=$request->qr_code;
            $elevator->id_location=$request->id_location;
            $elevator->save();
            return new ElevatorResource($elevator);
    }

    /**
     * Display the specified resource.
     */
    public function show(Elevator $elevator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Elevator $elevator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Elevator $elevator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Elevator $elevator)
    {
        //
    }
}
