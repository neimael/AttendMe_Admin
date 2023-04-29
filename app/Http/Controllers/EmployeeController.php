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
        $employee->birthday_date=$request->birthday_date;
        if($request['avatar']){
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = time(). $file->getClientOriginalName();
            Storage::disk('public')->put('EmployeeAvatar/'.$filename,  File::get($file));
            $employee->avatar = $filename;;
        }
        else {
            $employee->avatar = '/files/icons8-scan-reconnaissance-faciale-100 (1).png';
        }   
        $employee->save();
        

    }


   
}
