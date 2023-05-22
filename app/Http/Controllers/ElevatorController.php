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
use App\Exports\ElevatorExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;


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
        $existingElevator= Elevator::where('name', $request->name)->first();
        if ($existingElevator) {
            return response()->json([
                'message' => 'Name already exists in the database.'
            ], 400);
        }
        $elevator->name = $request->name;
        $elevator->id_location = $location->id_location;
        $elevator->location()->save($location); // associate location with elevator
        $elevator->save();
        $areas = ['area1', 'area2', 'area3'];
       foreach ($areas as $area) {
          // Create a record in the qrcodes table
          $qrCodeRecord = Qrcodes::create([
            'mission' => $area,
            'id_elevator' => $elevator->id_elevator,
        ]);

         // Generate the QR code content
         $qrCodeContent = "Location: " . $location->ville . ' ' . $location->adress . ' ' . $location->longitude . '-' . $location->latitude . "\n"
         . 'Name: ' . $elevator->name . "\n"
         . 'Mission: ' . $area . "\n"
         . 'QR Code ID: ' . $qrCodeRecord->id_qr_code;

     // Generate the QR code image
     $qrCode = QrCode::format('png')->size(200)->generate($qrCodeContent);
     $qrCodePath = 'qrcodes/' . $area . '_' . $qrCodeRecord->id_qr_code . '.png'; 
     Storage::disk('public')->put($qrCodePath, $qrCode);
     // Update the qrcodes table with the QR code path
     $qrCodeRecord->qr_code = $qrCodePath;
     $qrCodeRecord->save();
    }
    
    return response()->json([
        'message' => 'Elevator created successfully.',
        'data' => $elevator
    ], 200);
    }
    public function getElevator($id)
{
    $elevator = qrcodes::with('elevator.location')->where('id_elevator', $id)->get();

    if (!$elevator) {
        return response()->json(['error' => ' elevator not found.'], 404);
    }

    return response()->json($elevator);
}
public function getOneElevator($id)
{
   $elevator = Elevator::with('location')->where('id_elevator', $id)->get();
    if (!$elevator) {
        return response()->json(['error' => ' elevator not found. '], 404);
    }
    return $elevator;
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
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $elevator = Elevator::findOrFail($id);
        $elevator->name = $request->name;
        
        // update or create the location
        $location = Location::updateOrCreate(
            [
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'adress' => $request->adress,
                'ville' => $request->ville,
            ],
            [
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'adress' => $request->adress,
                'ville' => $request->ville,
            ]
        );
        $elevator->id_location = $location->id_location;
        $elevator->save();
        
        // update the QR codes
        $areas = ['area1', 'area2', 'area3'];
        foreach ($areas as $area) {
            $qrCode = QrCode::format('png')->size(200)->generate("Location : ". $location-> ville.' '.$location->adress.' '.$location->longitude. '-' .$location->latitude ."\n".'Name : '.$elevator-> name ."\n" .'Mission : ' .$area);
            $qrCodePath = 'qrcodes/' . $area . '_' . $elevator->id_elevator . '.png';
            Storage::disk('public')->put($qrCodePath, $qrCode);
            
            // update the QR code or create a new one
            $qrCodeModel = QrCodes::updateOrCreate(
                [
                    'mission' => $area,
                    'id_elevator' => $elevator->id_elevator,
                ],
                [
                    'mission' => $area,
                    'qr_code' => $qrCodePath,
                    'id_elevator' => $elevator->id_elevator,
                ]
            );
        }
        
        return $elevator;
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $elevator = Elevator::find($id);

    if (!$elevator) {
        return response()->json(['message' => 'Elevator not found'], 404);
    }

    $elevator->delete();

    return response()->json(['message' => 'Elevator has been deleted successfully']);
}
public function export()
{
     $filename = 'elevators.xlsx'; // Desired filename
    $format = \Maatwebsite\Excel\Excel::XLSX; // Desired file format (XLSX or CSV)

    return Excel::download(new ElevatorExport(), $filename, $format);
   
}
public function exportToPDF()
    {
        $employees =Elevator::with('location')->get();
    
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        
        $pdf = PDF::loadView('exports.elevator_pdf', ['employees' => $employees]);
        $pdf->getDomPDF()->set_option('font_size', 4);
        $pdf->getDomPDF()->set_option('table_width_auto', false);
        $pdf->getDomPDF()->set_option('table_width', '100%');
    
        $pdf->getDomPDF()->setOptions($options);
        
        return $pdf->download('elevators.pdf');
        
    }


}
