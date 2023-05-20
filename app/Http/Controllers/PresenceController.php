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
use App\Models\AssignmentElevator;
use DateInterval;
use DateTime;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Exports\SinglePresenceExport; 

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presence=Presence::with(['employee','qrcodes.elevator.location'])->whereDate('attendance_day', today())
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
        $attr = $request->validate([
            'id_elevator' => 'required',
            'id_employee' => 'required',
            'check_in' => 'required',
            'attendance_day' => 'required',
            'check_out'=>'required'
           
        ]);
        $presence = Presence::create([
            'id_elevator' => $attr['id_elevator'],
            'id_employee' => $attr['id_employee'],
            'check_in' => $attr['check_in'],
            'check_out' => $attr['check_out'],
            'attendance_day' => $attr['attendance_day'],
            
        ]);
        $presence = Presence::with(['employee','qrcodes.elevator'])->where('id_presence', $presence->id_presence)->first();

            
        $qrCode =QrCode::format('png')->size(200)->generate("Employee :" . $presence->employee->first_name." ".$presence->employee->last_name ."\n". 
        "Check in :".$presence->check_in."\n".
        "Check out :".$presence->check_out."\n".
        "Attendance day :".$presence->attendance_day."\n".
        "Elevator :".$presence->qrcodes->elevator->name. " at " .$presence->qrcodes->mission);
        $qrCodePath = URL::to('/').'/storage/QrCodesPresence/' . time()  . '.png';
        $store = 'QrCodesPresence/' . time()  . '.png';
        Storage::disk('public')->put($store, $qrCode);
        $presence->update([
        'qrcode' => $qrCodePath,
        ]);
        return response([
            'presence' => $presence,
            'message' => 'Your presence has been modified successfully',
        ]);
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
        //getPresenceByIdEmployee
        public function getPresenceByIdEmployee($id)
        {
            $presence = Presence::with(['employee','qrcodes.elevator.location'])
                ->where('id_employee', $id)
                ->get();
            $assignment = AssignmentElevator::with(['employee', 'qrcode.elevator.location'])
                ->where('id_employee', $id)
                ->get(); 
            $result = [];
              // Sort assignments by start date in ascending order
            $assignment = $assignment->sortBy('start_date');
            foreach ($assignment as $assign) {
                $days = [];
                $startDate = new DateTime($assign->start_date);
                $endDate = new DateTime(min($assign->end_date, date('Y-m-d')));
                $currentDate = clone $startDate;
                
                $today = new DateTime(); // Get the current date
                
                while ($currentDate <= $endDate && $currentDate <= $today) {
                    $currentDay = $currentDate->format('Y-m-d');
                    
                    // Skip the current day
                    if ($currentDay == date('Y-m-d')) {
                        $currentDate->add(new DateInterval('P1D'));
                        continue;
                    }
                    
                    $days[] = $currentDay;
                    $currentDate->add(new DateInterval('P1D'));
                }
                
    
                $status = 'Absent';
        
                foreach ($days as $day) {
                    $found = false;
        
                    $checkIn = null; // Initialize check-in time
    $checkOut = null; // Initialize check-out time
    $selfie=null;
    $qrcode=null;
    $elevator=null;
    foreach ($presence as $p) {
        if ($p->attendance_day == $day) {
            $found = true; // Set the flag to true to indicate a match
            $checkIn = $p->check_in;
            $checkOut = $p->check_out;
            $selfie=$p->selfie;
            $qrcode=$p->qrcode;
            $elevator=$p->qrcodes->elevator->name ." at ".$p->qrcodes->mission ." in ".$p->qrcodes->elevator->location->ville;
            $assignTimeIn = DateTime::createFromFormat('H:i:s', $assign->time_in);
            $checkIin = DateTime::createFromFormat('H:i:s', $p->check_in);
            
            $timeDifference = $checkIin->diff($assignTimeIn);
            $timeDifferenceMinutes = ($timeDifference->h * 60) + $timeDifference->i;
            
            if ($checkIin <= $assignTimeIn || $timeDifferenceMinutes <= 15) {
                                $status = 'On Time';
                            } else {
                                $status = 'Late';
                            }
        
                            break;
                        }
                    }
        
                    if (!$found) {
                        $status = 'Absent';
                    }
                    $result[] = [
                        'day' => $day,
                        'status' => $status,
                        'employee'=> $assign->employee->first_name ." ". $assign->employee->last_name,
                        'id_employee'=>$assign->employee->id,
                        'check_in'=>$checkIn,
                        'check_out'=>$checkOut,
                        'selfie'=>$selfie,
                        'qrcode'=>$qrcode,
                        'elevator'=>$elevator
                    ];
                }
            }
        
            return $result;
        }
        public function singleexport($id)
        {
             $filename = 'presences.xlsx'; // Desired filename
             $format = \Maatwebsite\Excel\Excel::XLSX; // Desired file format (XLSX or CSV)
    
            return Excel::download(new SinglePresenceExport($id), $filename, $format);
           
        }

        // fetch presence //getPresenceByIdEmployee
        public function getPresenceByIdEmp(Request $request)
        {
            $attr = $request->validate([
                'id_employee' => 'required|integer|exists:users,id',
            ]);
            $presence = Presence::with(['employee','qrcodes.elevator.location'])
                ->where('id_employee', $attr['id_employee'])
                ->get();
            $assignment = AssignmentElevator::with(['employee', 'qrcode.elevator.location'])
                ->where('id_employee', $attr['id_employee'])
                ->get(); 
            $result = [];
              // Sort assignments by start date in ascending order
            $assignment = $assignment->sortBy('start_date');
            foreach ($assignment as $assign) {
                $days = [];
                $startDate = new DateTime($assign->start_date);
                $endDate = new DateTime(min($assign->end_date, date('Y-m-d')));
                $currentDate = clone $startDate;
                
                $today = new DateTime(); // Get the current date
                
                while ($currentDate <= $endDate && $currentDate <= $today) {
                    $currentDay = $currentDate->format('Y-m-d');
                    
                    // Skip the current day
                    if ($currentDay == date('Y-m-d')) {
                        $currentDate->add(new DateInterval('P1D'));
                        continue;
                    }
                    
                    $days[] = $currentDay;
                    $currentDate->add(new DateInterval('P1D'));
                }
                
    
                $status = 'Absent';
        
                foreach ($days as $day) {
                    $found = false;
        
                    $checkIn = null; // Initialize check-in time
    $checkOut = null; // Initialize check-out time
    $selfie=null;
    $qrcode=null;
    $elevator=null;
    $longitude=null;
    $latitude=null;
    $id_presence=null;
    $lateTime=null;
    foreach ($presence as $p) {
        if ($p->attendance_day == $day) {
            $found = true; // Set the flag to true to indicate a match
            $checkIn = $p->check_in;
            $checkOut = $p->check_out;
            $selfie=$p->selfie;
            $qrcode=$p->qrcode;
            $elevator=$p->qrcodes->elevator->name ." at ".$p->qrcodes->mission ." in ".$p->qrcodes->elevator->location->ville;
            $assignTimeIn = DateTime::createFromFormat('H:i:s', $assign->time_in);
            $checkIin = DateTime::createFromFormat('H:i:s', $p->check_in);
            $longitude=$p->qrcodes->elevator->location->longitude;
            $latitude=$p->qrcodes->elevator->location->latitude;
            $id_presence=$p->id_presence;
            $timeDifference = $checkIin->diff($assignTimeIn);
            $timeDifferenceMinutes = ($timeDifference->h * 60) + $timeDifference->i;
            $lateTime=$timeDifferenceMinutes-15;

            
            if ($checkIin <= $assignTimeIn || $timeDifferenceMinutes <= 15) {
                                $status = 'On Time';
                            } else {
                                $status = 'Late';
                            }
        
                            break;
                        }
                    }
        
                    if (!$found) {
                        $status = 'Absent';
                    }
                    $result[] = [
                        'day' => $day,
                        'status' => $status,
                        'employee'=> $assign->employee->first_name ." ". $assign->employee->last_name,
                        'id_employee'=>$assign->employee->id,
                        'check_in'=>$checkIn,
                        'check_out'=>$checkOut,
                        'selfie'=>$selfie,
                        'qrcode'=>$qrcode,
                        'elevator'=>$elevator,
                        'longitude'=>$longitude,
                        'latitude'=>$latitude,
                        'id_presence'=>$id_presence,
                        'lateTime'=>$lateTime
                    ];
                }
            }
        
            return $result;
        }

        // fetch presence //getPresenceByIdEmployee
        public function getPresenceForDashboard(Request $request)
{
    $attr = $request->validate([
        'id_employee' => 'required|integer|exists:users,id',
    ]);

    $currentMonthStart = date('Y-m-01');
    $currentMonthEnd = date('Y-m-t');

    $presence = Presence::with(['employee', 'qrcodes.elevator.location'])
        ->where('id_employee', $attr['id_employee'])
        ->whereBetween('attendance_day', [$currentMonthStart, $currentMonthEnd])
        ->get();

    $assignment = AssignmentElevator::with(['employee', 'qrcode.elevator.location'])
        ->where('id_employee', $attr['id_employee'])
        ->get();

    $result = [];
    $statusCounts = ['Absent' => 0, 'On Time' => 0, 'Late' => 0];

    // Sort assignments by start date in ascending order
    $assignment = $assignment->sortBy('start_date');

    foreach ($assignment as $assign) {
        $days = [];
        $startDate = new DateTime($assign->start_date);
        $endDate = new DateTime(min($assign->end_date, date('Y-m-d')));
        $currentDate = clone $startDate;

        $today = new DateTime(); // Get the current date

        while ($currentDate <= $endDate && $currentDate <= $today) {
            $currentDay = $currentDate->format('Y-m-d');

            // Check if the current day is within the current month and not today
            if ($currentDay >= $currentMonthStart && $currentDay <= $currentMonthEnd && $currentDay != date('Y-m-d')) {
                $days[] = $currentDay;
            }

            $currentDate->add(new DateInterval('P1D'));
        }

        foreach ($days as $day) {
            $found = false;

            foreach ($presence as $p) {
                if ($p->attendance_day == $day) {
                    $found = true; // Set the flag to true to indicate a match

                    $assignTimeIn = DateTime::createFromFormat('H:i:s', $assign->time_in);
                    $checkIn = DateTime::createFromFormat('H:i:s', $p->check_in);

                    $timeDifference = $checkIn->diff($assignTimeIn);
                    $timeDifferenceMinutes = ($timeDifference->h * 60) + $timeDifference->i;
                    
                    if ($checkIn <= $assignTimeIn || $timeDifferenceMinutes <= 15) {
                        $status = 'On Time';
                    } else {
                        $status = 'Late';
                    }

                    // Update the count for the current status
                    $statusCounts[$status]++;
                    break;
                }
            }

            if (!$found) {
                $statusCounts['Absent']++;
            }
        }
    }

    return response()->json($statusCounts); // Assuming this is returning a JSON response
}


        // export pdf
        public function singleexportToPDF($id)
        {
            $employees =$this->getPresenceByIdEmployee($id);
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $pdf = PDF::loadView('exports.presence-employee_pdf', ['employees' => $employees]);
            $pdf->getDomPDF()->set_option('font_size', 4);
            $pdf->getDomPDF()->set_option('table_width_auto', false);
            $pdf->getDomPDF()->set_option('table_width', '100%');
        
            $pdf->getDomPDF()->setOptions($options);
            
            return $pdf->download('presences.pdf');
            
        }
        
       

 
}
