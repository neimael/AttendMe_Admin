<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\EmployeeCreated;
use Illuminate\Support\Facades\Mail;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Illuminate\Support\Facades\URL;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // Laravel Controller
public function index(Request $request)
{
    $searchTerm = $request->input('search');

    if (!empty($searchTerm)) {
        $users = User::where(function ($query) use ($searchTerm) {
            $query->where('cin', 'like', '%' . $searchTerm . '%')
                ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('phone_number', 'like', '%' . $searchTerm . '%')
                ->orWhere('adress', 'like', '%' . $searchTerm . '%');
        })->get();
    } else {
        $users = User::all();
    }

    return response()->json($users);
}

    



    public function store(request $request)
    {
        $employee = new User();
        $employee->first_name=$request->first_name;
        $employee->last_name=$request->last_name;
        $existingEmployee = User::where('email', $request->email)->first();
        if ($existingEmployee) {
            return response()->json([
                'message' => 'Email already exists in the database.'
            ], 400);
        }
    
        $employee->email=$request->email;
        $employee->phone_number=$request->phone_number;
        // Convert the image to base64 encoding
        $password = Str::random(8); // Generate an 8-character random password
        $employee->password = Hash::make($password); // Hash the password
        $employee->cin=$request->cin;
        $employee->adress=$request->adress;
        $employee->birthday=$request->birthday;
        if($request['avatar']){
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = time(). $file->getClientOriginalName();
            Storage::disk('public')->put('profiles/'.$filename,  File::get($file));
            $employee->avatar = URL::to('/').'/storage/profiles/'.$filename;
        }
         
        $employee->save();
        try {
            Mail::to($request->input('email'))->send(new EmployeeCreated($request->input('email'), $password));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error sending email: ' . $e->getMessage()
            ], 500);
        }
        return response()->json([
            'message' => 'Employee added successfully.',
            'password' => $password // Return the generated password
        ]);

    }
    public function destroy($id)
    {
        $employee = User::find($id);
    
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
    
        $employee->delete();
    
        return response()->json(['message' => 'Employee has been deleted successfully']);
    }
    public function getEmployee($id)
{
    $employee = User::find($id);

    if (!$employee) {
        return response()->json(['error' => 'Employee not found.'], 404);
    }

    return response()->json($employee);
}
public function update(Request $request, $id)
{
    $employee = User::findOrFail($id);
    $employee->first_name = $request->input('first_name');
    $employee->last_name = $request->input('last_name');
    
    $existingEmployee = User::where('email', $request->email)->where('id','!=', $id)->first();
    if ($existingEmployee) {
        return response()->json([
            'message' => 'Email already exists in the database.'
        ], 400);
    }
    $employee->email = $request->input('email');
    $employee->phone_number = $request->input('phone_number');
    $employee->cin = $request->input('cin');
    $employee->adress = $request->input('adress');
    $employee->birthday = $request->input('birthday');
    
    if (!empty($request->avatar)) {
        $image = $this->saveImage($request->avatar, 'profiles');
        $employee->avatar = $image;
    }

    

    $employee->save();

    return response()->json([
        'message' => 'Employee updated successfully.',
    ]);
} 

public function export()
{
     $filename = 'employees.xlsx'; // Desired filename
    $format = \Maatwebsite\Excel\Excel::XLSX; // Desired file format (XLSX or CSV)

    return Excel::download(new EmployeeExport(), $filename, $format);
   
}
public function exportToPDF()
    {
        $employees = User::all();
    
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        
        $pdf = PDF::loadView('exports.employee_pdf', ['employees' => $employees]);
        $pdf->getDomPDF()->set_option('font_size', 4);
        $pdf->getDomPDF()->set_option('table_width_auto', false);
    $pdf->getDomPDF()->set_option('table_width', '100%');
    
    $pdf->getDomPDF()->setOptions($options);
        
        return $pdf->download('employees.pdf');
        
    }

   
}
