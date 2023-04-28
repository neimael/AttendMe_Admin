<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees=User::all();
        //return $employees[0]-> sanitaryIssues;
        return  EmployeeResource::collection($employees);
        
        //
    }

   
}
