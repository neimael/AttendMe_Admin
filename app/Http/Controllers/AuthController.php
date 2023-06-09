<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Presence;
use App\Models\AssignmentElevator;
use App\Models\PresenceRegulation;
use App\Models\SanitaryIssues;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Nexmo\Client;
use Nexmo\Client\Credentials\Basic;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;






class AuthController extends Controller
{

    //register 
    public function register(Request $request){
        $attr = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'avatar' => 'required|string',
            'cin' => 'required|string',
            'birthday' => 'required|date',
            'phone_number' => 'required|string',
            'adress' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create([
            'first_name' => $attr['first_name'],
            'last_name' => $attr['last_name'],
            'avatar' => $attr['avatar'],
            'cin' => $attr['cin'],
            'birthday' => $attr['birthday'],
            'phone_number' => $attr['phone_number'],
            'adress' => $attr['adress'],
            'email' => $attr['email'],
            'password' => bcrypt($attr['password']),
        ]);

        return response([
            'user' => $user,
            'token' => $user -> createToken('secret') -> plainTextToken,
        ]);
    }

    //login
    public function login(Request $request){
        $attr = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if(!Auth::attempt($attr)){
            return response([
                'errors' => 'Invalid credentials'
            ], 403);
        }

        return response([
            'user' => auth() ->user(),
            'token' => auth() -> user() ->  createtoken('secret') -> plainTextToken
        ], 200);
    }

    //logout 
    public function logout(Request $request){
        auth() -> user() ->tokens() -> delete() ;
        return response([
            'message' => 'Logout Success'
        ], 200);
    }

    //user
    public function user(){
        return response([
            'user' => auth() ->user()
        ],200);
    }

    //update
    public function update(Request $request)
    {
        $attr = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birthday' => 'required|date',
            'phone_number' => 'required|string',
            'adress' => 'required|string',
            'email' => 'required|email',
        ]);

        $image = $this->saveImage($request->avatar, 'profiles');

        auth()->user()->update([
            'first_name' => $attr['first_name'],
            'last_name' => $attr['last_name'],
            'birthday' => $attr['birthday'],
            'phone_number' => $attr['phone_number'],
            'adress' => $attr['adress'],
            'email' => $attr['email'],
            'avatar' => $image
        ]);

        return response([
            'message' => 'User updated.',
            'user' => auth()->user()
        ], 200);
    }

    //add presence 
    public function addPresence(Request $request)
    {
        $attr = $request->validate([
            'id_elevator' => 'required',
            'id_employee' => 'required',
            'check_in' => 'required',
            'attendance_day' => 'required',
            'selfie' => 'String',
        ]);

        $image = $this->saveImage($request->selfie, 'Selfies');

        $presence = Presence::create([
            'id_elevator' => $attr['id_elevator'],
            'id_employee' => $attr['id_employee'],
            'check_in' => $attr['check_in'],
            'attendance_day' => $attr['attendance_day'],
            'selfie' =>  $image,
        ]);
        

        return response([
            'presence' => $presence,
            'message' => 'Your CheckIn has been saved successfully',
        ]);
    }
    public function updatePresence(Request $request)
    {
        // $presence = Presence::with(['employee','qrcodes.elevator'])->findOrFail($id);
        $attr = $request->validate([
          'check_out' => 'required|time',
          'id_presence' => 'required'
        ]);

        $presence = Presence::with(['employee','qrcodes.elevator'])->where('id_presence', $attr['id_presence'])->first();

         $presence->update([
                    'check_out' => $attr['check_out'],  
                    // $qrCodePath = 'qrcodes/' . $area . '_' . $elevator->id_elevator . '.png';
                    // Storage::disk('public')->put($qrCodePath, $qrCode);
        ]);
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
            'message' => 'Your presence has been saved successfully',
        ]);
    }
    //get presnece_id 
    public function getIdPresence(Request $request)
{

    $attr = $request->validate([
        'id_employee' => 'required',
        'id_elevator' => 'required',
        'check_in' => 'required',
        'attendance_day' => 'required',
    ]);



    $presence = Presence::where('id_employee', $attr["id_employee"])
        ->where('id_elevator', $attr["id_elevator"])
        ->where('check_in', $attr["check_in"])
        ->where('attendance_day', $attr["attendance_day"])
        ->first();

        return response($presence);

    }
    //Change Password
    public function changePassword(Request $request)
    {
        try {
            $input = $request -> all();
            $validator = Validator::make($input, [
                'old_password' => 'required|min:8',
                'new_password' => 'required|min:8',

            ]);

            if ($validator->fails()) {
                return response() -> json([
                    
                    'message' => 'Validation Error',

                    
                ], 422);
                
            } 

            if(!Hash::check($input['old_password'], $request -> user() -> password)) {
                return response() -> json([
                    'message' => 'Old password is incorrect',
                ], 401);
            }

            $input['password'] = Hash::make($input['new_password']);
            $request -> user() -> update($input);
            return response() -> json([
                'message' => "Password updated successfully",
                'data' => $request->user(),
            ]);
        } catch (\Throwable $th) {
            return response() -> json([
                'message' => $th -> getMessage(),
            ], 500);        
        }
    }

    //Change Password
    public function changePassword2(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'new_password' => 'required|min:8'
        ]);

        // Get the user with the specified email from the database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 401);
        }

        if (Hash::check($request->new_password, $user -> password)) {
            return response() -> json([
                             'message' => 'New password should be different from the previous',
                        ], 401);
    
                    }

            $user -> password = Hash::make($request ->new_password);
            $user->save();

        return response()->json(['status' => 'success', 'message' => 'Password updated successfully']);
            
    }

    //addAttIssues 
    public function addAttIssues(Request $request){
        $attr = $request->validate([
            'check_in' => 'required|time',
            'check_out' => 'required|time',
            'attendance_day' => 'required|date',
            'issue_type' => 'required|string',
            'status' => 'required|string',
            'report' => 'required|String',
            'id_employee' => 'required|integer|exists:users,id'
        ]);

        $presence_regulation = PresenceRegulation::create([
            'check_in' => $attr['check_in'],
            'check_out' => $attr['check_out'],
            'attendance_day' => $attr['attendance_day'],
            'issue_type' => $attr['issue_type'],
            'status' => $attr['status'],
            'report' => $attr['report'],
            'id_employee' => $attr['id_employee'],
        ]);

        return response([
            'presence_regulation' => $presence_regulation,
            'message' => 'Your regulation has been sent successfully',
        ]);
    }

    //add Sanitary Issues
    public function addSanitary(Request $request){
        $attr = $request->validate([
            'id_employee' => 'required|integer|exists:users,id',
            'report' => 'required|string',
            'certificate' => 'required|String',
            'extension' => 'required|String',
        ]);
        $file = $this->saveFile($request->certificate, 'certificates', $request->extension);
        $sanitary_regulation = SanitaryIssues::create([
            'id_employee' => $attr['id_employee'],
            'report' => $attr['report'],
            'certificate' => $file,
        ]);

        return response([
            'sanitary_regulation' => $sanitary_regulation,
            'message' => 'Your certificate has been sent successfully',
        ]);
    }


    //sendOTP
    public function sendOTP(Request $request)
    {
        // Validate email input
        $this->validate($request, [
            'email' => 'required|email'
        ]);
    
        // Get the user with the specified email from the database
        $user = User::where('email', $request->email)->first();
    
        // if (!$user) {
        //     return response()->json(['status' => 'error', 'message' => 'User not found']);
        // }
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 401);
        }
    
        // Generate a random 4-digit OTP code
        $otp = mt_rand(1000, 9999);
    
        // Send the message via email
        try {
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Your OTP code for AttendMe');
            });
    
            // Store the OTP code in the user's session for verification later
            $user->otp_code = $otp;
            $user->save();
    
            return response()->json(['status' => 'success', 'message' => 'OTP code sent successfully']);
        } catch (Exception $e) {
            // Handle email sending exceptions here
            return response()->json(['status' => 'error', 'message' => 'Failed to send OTP code']);
        }
    }

    //verify the otp code
    public function verifyOTP(Request $request)
{
    // Validate input
    $this->validate($request, [
        'email' => 'required|email',
        'otp_code' => 'required|numeric'
    ]);

    // Get the user with the specified email from the database
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'User not found'], 401);
    }

    // Verify the OTP code
    if ($user->otp_code == $request->otp_code) {
        // Clear the OTP code from the user's session
        $user->otp_code = null;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'OTP code verified successfully']);
    } else {
        return response()->json(['status' => 'error', 'message' => 'Invalid OTP code'], 401);
    }
}

 // Get assignmentElevator
public function getAssignmentElevator(Request $request) {
    $attr = $request->validate([
        'id_employee' => 'required|integer|exists:users,id',
    ]);
    
    $assignments = AssignmentElevator::where('id_employee', $attr['id_employee'])->get();

    if ($assignments->isEmpty()) {
        return response()->json([
            'message' => 'No assignments found for the provided employee ID',
        ], 200);
    } else {
        return response()->json([
            'assignments' => $assignments,
            'message' => 'Assignments have been fetched successfully',
        ], 200);
    }
}

 // Get Presence
public function getPresence(Request $request) {
    $attr = $request->validate([
        'id_employee' => 'required|integer|exists:users,id',
    ]);
    
    $presence = Presence::where('id_employee', $attr['id_employee'])->get();

    if ($presence->isEmpty()) {
        return response()->json([
            'message' => 'No Presence found for the provided employee ID',
        ], 200);
    } else {
        return response()->json([
            'presence' => $presence,
            'message' => 'Presence have been fetched successfully',
        ], 200);
    }
}

public function getPresenceById(Request $request)
{
    $attr = $request->validate([
        'id_presence' => 'required',
    ]);
    
    $presence = Presence::where('id_presence', $attr['id_presence'])->first();

    return response($presence);

}

    


    

}
