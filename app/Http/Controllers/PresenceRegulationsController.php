<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresenceRegulationRequest;
use App\Http\Resources\PresenceRegulationResource;
use App\Models\PresenceRegulation;
use Illuminate\Http\Request;
use App\Exports\RegulationExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;


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
    public function export()
    {
         $filename = 'regulations.xlsx'; // Desired filename
        $format = \Maatwebsite\Excel\Excel::XLSX; // Desired file format (XLSX or CSV)
    
        return Excel::download(new RegulationExport(), $filename, $format);
       
    }
    public function exportToPDF()
        {
            $employees =PresenceRegulation::with(['employee'])->get();
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $pdf = PDF::loadView('exports.regulation_pdf', ['employees' => $employees]);
            $pdf->getDomPDF()->set_option('font_size', 4);
            $pdf->getDomPDF()->set_option('table_width_auto', false);
            $pdf->getDomPDF()->set_option('table_width', '100%');
        
            $pdf->getDomPDF()->setOptions($options);
            
            return $pdf->download(' regulations.pdf');
            
        }
    
}
