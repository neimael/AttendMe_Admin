<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresenceRequest;
use App\Http\Resources\PresenceResource;
use App\Models\Presence;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presence=Presence::with(['employee','qrcodes.elevator.location'])->whereDate('created_at', today())
        ->get();
        return $presence;
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
    public function store(StorePresenceRequest $request)
    {
            $presence=new Presence();
            $presence->id_employee=$request->id_employee;
            $presence->id_elevator=$request->id_elevator;
            $presence->check_in=$request->check_in;
            $presence->check_out=$request->check_out;
            $presence->attendance_day=$request->attendance_day;
            $presence->qrcode=$request->qrcode;
            if ($request->has('selfie')) {
                $image = $request->file('selfie');
                $base64Image = base64_encode(file_get_contents($image->getPathname()));
                $presence->selfie = $base64Image;
            }
            $presence->save();
            return new PresenceResource($presence);
    }

    /**
     * Display the specified resource.
     */
    public function show(Presence $presence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presence $presence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presence $presence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presence $presence)
    {
        //
    }
}
