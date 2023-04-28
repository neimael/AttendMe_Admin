<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $add_admin=new Admin();
        $add_admin->first_name=$request->first_name;
        $add_admin->last_name=$request->last_name;
        $add_admin->email=$request->email;
        $add_admin->phone_number=$request->phone_number;
        // Convert the image to base64 encoding
        //if ($request->has('avatar')) {
            // Store the uploaded file and get its URL
           // $avatarUrl = Storage::url($request->file('avatar')->store('public/avatars'));
    
            // Save the URL to the database
           // $add_admin->avatar = $avatarUrl;
        //}
       $add_admin->avatar="https://images.ctfassets.net/hrltx12pl8hq/3j5RylRv1ZdswxcBaMi0y7/b84fa97296bd2350db6ea194c0dce7db/Music_Icon.jpg";
        $add_admin->password=$request->password;
        $add_admin->save();
        return new $add_admin;

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
