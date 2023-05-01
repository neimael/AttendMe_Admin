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


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees=User::all();
        return $employees;
        //
    }
    public function store(request $request)
    {
        $employee = new User();
        $employee->first_name=$request->first_name;
        $employee->last_name=$request->last_name;
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
            Storage::disk('public')->put('EmployeeAvatar/'.$filename,  File::get($file));
            $employee->avatar = $filename;;
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
            'message' => 'Admin added successfully.',
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

   
}
