<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresenceRequest;
use App\Http\Resources\PresenceResource;
use App\Models\Presence;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use App\Exports\PresenceExport;


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

    public function getPresence($id)
    {
        $presence = Presence::with(['employee','qrcodes.elevator.location'])->find($id);

        if (!$presence) {
            return response()->json(['error' => 'presence not found.'], 404);
        }
    
        return response()->json($presence);
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
    public function export()
    {
         $filename = 'presences.xlsx'; // Desired filename
        $format = \Maatwebsite\Excel\Excel::XLSX; // Desired file format (XLSX or CSV)
    
        return Excel::download(new PresenceExport(), $filename, $format);
       
    }
    public function exportToPDF()
        {
            $employees =Presence::with(['employee','qrcodes.elevator.location'])->get();
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $pdf = PDF::loadView('exports.presence_pdf', ['employees' => $employees]);
            $pdf->getDomPDF()->set_option('font_size', 4);
            $pdf->getDomPDF()->set_option('table_width_auto', false);
            $pdf->getDomPDF()->set_option('table_width', '100%');
        
            $pdf->getDomPDF()->setOptions($options);
            
            return $pdf->download(' presences.pdf');
            
        }
 
}
