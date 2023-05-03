<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PresenceRegulation;
use App\Models\SanitaryIssues;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;




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
    // public function sendOTP(Request $request)
    // {
    //     // Validate phone number input
    //     $this->validate($request, [
    //         'phone_number' => 'required|regex:/^\+?[1-9]\d{1,14}$/'
    //     ]);

    //     // Generate a random 4-digit OTP code
    //     $otp = mt_rand(1000, 9999);

    //     // Your Twilio account SID and auth token from https://www.twilio.com/console
    //     $account_sid = 'YOUR_ACCOUNT_SID';
    //     $auth_token = 'YOUR_AUTH_TOKEN';

    //     // Your Twilio phone number from https://www.twilio.com/console/phone-numbers/incoming
    //     $twilio_number = '+1415XXXXXXX';

    //     // Initialize the Twilio client
    //     $client = new Client($account_sid, $auth_token);

    //     // Send the message via Twilio
    //     try {
    //         $message = $client->messages->create(
    //             $request->phone_number,
    //             array(
    //                 'from' => $twilio_number,
    //                 'body' => "Your OTP code fir AttendMe Application is: $otp"
    //             )
    //         );

    //         // Store the OTP code in the session for verification later
    //         $request->session()->put('otp_code', $otp);
    //         $request->session()->put('phone_number', $request->phone_number);

    //         return response()->json(['status' => 'success', 'message' => 'OTP code sent successfully']);
    //     } catch (\Exception $e) {
    //         // Handle Twilio exceptions here
    //         return response()->json(['status' => 'error', 'message' => 'Failed to send OTP code']);
    //     }
    // }
}
