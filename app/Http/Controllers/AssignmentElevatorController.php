<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsignmentElevator;
use App\Models\AssignmentElevator;
use Illuminate\Http\Request;
use App\Http\Resources\AssignmentElevatorResource;
use App\Exports\AsignmentExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;


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

    public function export()
{
     $filename = 'asignments.xlsx'; // Desired filename
    $format = \Maatwebsite\Excel\Excel::XLSX; // Desired file format (XLSX or CSV)

    return Excel::download(new AsignmentExport(), $filename, $format);
   
}
public function exportToPDF()
    {
        $employees =AssignmentElevator::with(['employee','qrcode.elevator.location'])->get();
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf = PDF::loadView('exports.asignment_pdf', ['employees' => $employees]);
        $pdf->getDomPDF()->set_option('font_size', 4);
        $pdf->getDomPDF()->set_option('table_width_auto', false);
        $pdf->getDomPDF()->set_option('table_width', '100%');
    
        $pdf->getDomPDF()->setOptions($options);
        
        return $pdf->download(' asignments.pdf');
        
    }


}

