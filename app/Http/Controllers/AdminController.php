<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins=Admin::all();
        //return $employees[0]-> sanitaryIssues;
        return  AdminResource::collection($admins);
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
    public function store(StoreAdminRequest $request)
    {
        $admin=new Admin();
        $admin->first_name=$request->first_name;
        $admin->last_name=$request->last_name;
        $admin->email=$request->email;
        $admin->phone_number=$request->phone_number;
        // Convert the image to base64 encoding
        if ($request->has('avatar')) {
            $image = $request->file('avatar');
            $base64Image = base64_encode(file_get_contents($image->getPathname()));
            $admin->avatar = $base64Image;
        }
        $admin->password=$request->password;
        $admin->save();
        return new AdminResource($admin);

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
