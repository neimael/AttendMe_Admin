<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins=Admin::all();
        //return $employees[0]-> sanitaryIssues;
        return  $admins;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(request $request)
    {
      
    
        $add_admin = new Admin();
        $add_admin->first_name = $request->input('first_name');
        $add_admin->last_name = $request->input('last_name');
        $add_admin->email = $request->input('email');
        $add_admin->phone_number = $request->input('phone_number');
        
        $password = Str::random(8); // Generate an 8-character random password
        $add_admin->password = Hash::make($password); // Hash the password
        
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . $file->getClientOriginalName();
            Storage::disk('public')->put('AdminAvatar/'.$filename,  File::get($file));
            $add_admin->avatar = $filename;
        } else {
            $add_admin->avatar = '/files/icons8-scan-reconnaissance-faciale-100 (1).png';
        }
           
        $add_admin->save();
        
        return response()->json([
            'message' => 'Admin added successfully.',
            'password' => $password // Return the generated password
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
