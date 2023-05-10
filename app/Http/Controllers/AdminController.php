<?php

namespace App\Http\Controllers;

use App\Exports\AdminExport;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\AdminCreated;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;

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
        } 
        $add_admin->save();
        try {
            Mail::to($request->input('email'))->send(new AdminCreated($request->input('email'), $password));
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

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->first_name = $request->input('first_name');
        $admin->last_name = $request->input('last_name');
        $admin->email = $request->input('email');
        $admin->phone_number = $request->input('phone_number');
    
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . $file->getClientOriginalName();
            Storage::disk('public')->put('AdminAvatar/'.$filename,  File::get($file));
            $admin->avatar = $filename;
        } 
        $admin->save();
    
        return response()->json([
            'message' => 'Admin updated successfully.',
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $admin = Admin::find($id);

    if (!$admin) {
        return response()->json(['message' => 'Admin not found'], 404);
    }

    $admin->delete();

    return response()->json(['message' => 'Admin has been deleted successfully']);
}
public function getAdmin($id)
{
    $admin = Admin::find($id);

    if (!$admin) {
        return response()->json(['error' => 'admin not found.'], 404);
    }

    return response()->json($admin);
}
public function export()
{
     $filename = 'admins.xlsx'; // Desired filename
    $format = \Maatwebsite\Excel\Excel::XLSX; // Desired file format (XLSX or CSV)

    return Excel::download(new AdminExport(), $filename, $format);
   
}
public function exportToPDF()
    {
        $employees = Admin::all();
    
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        
        $pdf = PDF::loadView('exports.admin_pdf', ['employees' => $employees]);
        $pdf->getDomPDF()->set_option('font_size', 4);
        $pdf->getDomPDF()->set_option('table_width_auto', false);
        $pdf->getDomPDF()->set_option('table_width', '100%');
    
        $pdf->getDomPDF()->setOptions($options);
        
        return $pdf->download('admins.pdf');
        
    }
    public function loginAdmin(Request $request){
      
    $attr = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8'
    ]);

    $admin = Admin::where('email', $attr['email'])->first();

    if (!$admin || !Hash::check($attr['password'], $admin->password)) {
        return response([
            'errors' => 'Invalid credentials'
        ], 403);
    }

    // Log in the admin user
    Auth::guard('admin')->login($admin);

    return response([
        'admin' => $admin,
    ], 200);
    }
    public function user(){
        if (!auth()->guard('admin')->check()) {
            return response([
                'errors' => 'Unauthorized'
            ], 401);
        }
    
        $admin = auth()->guard('admin')->user();
    
        return response([
            'user' => $admin
        ], 200);
    }
    // public function logout(){
    //     auth()->user()->tokens()->delete();
    //     return response([
    //         'message' => 'Logged out'
    //     ],200);
    // }

}
