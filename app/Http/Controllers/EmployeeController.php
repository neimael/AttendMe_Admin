<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;
use Carbon\Carbon;

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
    public function store(StoreEmployeeRequest $request)
    {
        $employee = new User();
        $employee->first_name=$request->first_name;
        $employee->last_name=$request->last_name;
        $employee->email=$request->email;
        $employee->phone_number=$request->phone_number;
        // Convert the image to base64 encoding
        $employee->avatar = "https://images.ctfassets.net/hrltx12pl8hq/3j5RylRv1ZdswxcBaMi0y7/b84fa97296bd2350db6ea194c0dce7db/Music_Icon.jpg";
        $employee->password=$request->password;
        $employee->cin=$request->cin;
        $employee->birthday_date=$request->birthday_date;
        $employee->save();
        return $employee;

    }


   
}
