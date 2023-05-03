<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreElevator;
use App\Http\Resources\ElevatorResource;
use App\Models\Elevator;
use App\Models\Location;
use App\Models\qrcodes;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ElevatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $elevators=qrcodes::with('elevator.location')->get();
        return $elevators;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function cities()
    {
        $table_name = 'location';
        $column_name = 'ville'; // replace with your enum column name

        $query = "SHOW COLUMNS FROM {$table_name} WHERE Field = '{$column_name}'";
        $result = DB::select($query);

        preg_match('/^enum\((.*)\)$/', $result[0]->Type, $matches);
        $enum_values = array();

        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum_values[] = $v;
        }

        return response()->json(['data' => $enum_values]);
    }
   
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            
           // create or retrieve the location
           $location = Location::firstOrCreate([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'adress' => $request->adress,
            'ville' => $request->ville,
        ]);
    
        // create and save the elevator
        $elevator = new Elevator();
        $elevator->name = $request->name;
        $elevator->id_location = $location->id_location;
        $elevator->location()->save($location); // associate location with elevator
        $elevator->save();
        $areas = ['area1', 'area2', 'area3'];
       foreach ($areas as $area) {
        $qrCode = QrCode::format('png')->size(200)->generate("Location : ". $location-> ville.' '.$location->adress.' '.$location->longitude. '-' .$location->latitude ."\n".'Name : '.$elevator-> name ."\n" .'Mission : ' .$area);
        $qrCodePath = 'qrcodes/' . $area . '_' . $elevator->id_elevator . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCode);
        qrcodes::create([
            'mission' => $area,
            'qr_code' => $qrCodePath,
            'id_elevator' => $elevator->id_elevator,
        ]);
    }
    
        return $elevator;
    }
    public function getElevator($id)
{
    $elevator = qrcodes::with('elevator.location')->where('id_elevator', $id)->get();

    if (!$elevator) {
        return response()->json(['error' => ' elevator not found.'], 404);
    }

    return response()->json($elevator);
}

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $elevators=Elevator::with('location')->get();
        return $elevators;
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
