<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
}
