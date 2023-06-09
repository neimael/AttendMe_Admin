<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsignmentElevator;
use App\Models\AssignmentElevator;
use Illuminate\Http\Request;
use App\Http\Resources\AssignmentElevatorResource;
use App\Exports\AsignmentExport;
use App\Models\Elevator;
use App\Models\qrcodes;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AffectationCreated;
use App\Mail\AffectationModified;

class AssignmentElevatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
    
        if (!empty($searchTerm)) {
            $assignmentElevator = AssignmentElevator::with(['employee', 'qrcode.elevator.location'])
              
                ->where(function ($query) use ($searchTerm) {
                    $query->whereHas('employee', function ($query) use ($searchTerm) {
                        $query->where('first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('qrcode.elevator.location', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('ville', 'like', '%' . $searchTerm . '%');
                    });
                })
                ->get();
        } else {
            $assignmentElevator = AssignmentElevator::with(['employee', 'qrcode.elevator.location'])
       
                ->get();
        }
    
        return response()->json($assignmentElevator);
      
    }
    public function missions()
    {
        $table_name = 'qrcode';
        $column_name = 'mission'; // replace with your enum column name

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
    public function getNames()
    {
        $elevators = Elevator::pluck('name');
        
        return response()->json($elevators);
    }
    public function getEmployees()
{
    $employees = User::select(DB::raw("CONCAT(first_name, ' ', last_name, ' (', cin, ')') AS name"))->get();
    
    return response()->json($employees);
}

public function getInformation(Request $request)
{
    // Retrieve the selected elevator and mission from the request
    $selectedElevator = $request->input('elevator');
    $selectedMission = $request->input('mission');

    // Retrieve the QR code information with the related elevator and location
    $qrcode = Qrcodes::with(['elevator.location'])
        ->whereHas('elevator', function ($query) use ($selectedElevator) {
            $query->where('name', $selectedElevator);
        })
        ->where('mission', $selectedMission)
        ->first();

    // Initialize the location information
    $locationInformation = null;

    // Check if the QR code and related elevator exist
    if ($qrcode && $qrcode->elevator && $qrcode->elevator->location) {
        // Retrieve the location information
        $location = $qrcode->elevator->location;

        $locationInformation = [
            'ville' => $location->ville,
            'adress' => $location->adress,
            'id' => $qrcode->id_qr_code
        ];
    }

    // Return the information as a JSON response
    return response()->json([
        'qrcode' => $qrcode,
        'mission' => $selectedMission,
        'location' => $locationInformation,
    ]);
}

    public function getEmployeeInfo(Request $request)
    {
        $selectedEmployee = $request->input('cin');

    // Extract the cin value from the selectedEmployee string
    preg_match('/\(([^)]+)\)/', $selectedEmployee, $matches);
    $cin = isset($matches[1]) ? $matches[1] : null;
        $employee = User::where('cin', $cin)->first();
        if ($employee) {
            $employeeInformation = [
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'email' => $employee->email,
                'phone_number' => $employee->phone_number,
                'cin'=>$employee->cin,
                'adress'=>$employee->adress,

                
            ];
        }

        // Return the information as a JSON response
        return response()->json([
            'employee' => $employee,
            'employeeIn' => $employeeInformation,
        ]);
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id_employee = $request->id_employee;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
    
        // Check if there are any conflicting assignments for the same employee and overlapping dates
        $conflictingAssignment = AssignmentElevator::where('id_employee', $id_employee)
            ->where(function ($query) use ($start_date, $end_date) {
                $query->whereBetween('start_date', [$start_date, $end_date])
                    ->orWhereBetween('end_date', [$start_date, $end_date])
                    ->orWhere(function ($query) use ($start_date, $end_date) {
                        $query->where('start_date', '<=', $start_date)
                            ->where('end_date', '>=', $end_date);
                    });
            })
            ->first();
    
        if ($conflictingAssignment) {
            return response()->json(['message' => 'This assignment conflicts with an existing assignment. Please choose different dates.'], 422);
        }
    
        $assignmentElevator = new AssignmentElevator();
        $assignmentElevator->id_employee = $id_employee;
        $assignmentElevator->id_elevator = $request->id_elevator;
        $assignmentElevator->start_date = $start_date;
        $assignmentElevator->end_date = $end_date;
        $assignmentElevator->time_in = $request->time_in;
        $assignmentElevator->time_out = $request->time_out;
        $assignmentElevator->save();
        try {
            $employee = User::find($id_employee);
            $qrcode = qrcodes::find($request->id_elevator);

            Mail::to($employee->email)->send(new AffectationCreated($request->time_in,$request->time_out,$start_date,$end_date,$qrcode->elevator->name.' '.$qrcode->mission.' '.$qrcode->elevator->location->ville.' '.$qrcode->elevator->location->adress));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error sending email: ' . $e->getMessage()
            ], 500);
        }
        return response()->json(['message' => 'Assignment has been created successfully']);
    }
    

 //get assignment by id
    public function getAssignment($id)
    {
        $assignmentElevator = AssignmentElevator::with(['employee','qrcode.elevator.location'])->where('id_assignment_elevator', $id)->get();
        if (!$assignmentElevator) {
            return response()->json(['message' => 'Asignment not found'], 404);
        }
        return $assignmentElevator;
        
    }
    public function update(Request $request, $id){

    $id_employee = $request->id_employee;
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    $conflictingAssignment = AssignmentElevator::where('id_employee', $id_employee)
    ->where('id_assignment_elevator', '!=', $id)
    ->where(function ($query) use ($start_date, $end_date) {
        $query->whereBetween('start_date', [$start_date, $end_date])
            ->orWhereBetween('end_date', [$start_date, $end_date])
            ->orWhere(function ($query) use ($start_date, $end_date) {
                $query->where('start_date', '<=', $start_date)
                    ->where('end_date', '>=', $end_date);
            });
    })
    ->first();

if ($conflictingAssignment) {
    return response()->json(['message' => 'This assignment conflicts with an existing assignment. Please choose different dates.'], 422);
}

    $assignmentElevator = AssignmentElevator::findOrFail($id);
    
    $assignmentElevator->id_employee = $request->id_employee;
    $assignmentElevator->id_elevator = $request->id_elevator;
    
    $assignmentElevator->start_date = $request->start_date;
    $assignmentElevator->end_date = $request->end_date;
    $assignmentElevator->time_in = $request->time_in;
    $assignmentElevator->time_out = $request->time_out;
    
    $assignmentElevator->save();
    try {
        $employee = User::find($id_employee);
        $qrcode = qrcodes::find($request->id_elevator);

        Mail::to($employee->email)->send(new AffectationModified($request->time_in,$request->time_out,$start_date,$end_date,$qrcode->elevator->name.' '.$qrcode->mission.' '.$qrcode->elevator->location->ville.' '.$qrcode->elevator->location->adress));
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error sending email: ' . $e->getMessage()
        ], 500);
    }
    return response()->json(['message' => 'Assignment has been updated successfully']);
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

