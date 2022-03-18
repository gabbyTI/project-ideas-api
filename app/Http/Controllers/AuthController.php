<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $users;

    public function __construct(IUser $users){
        $this->users = $users;
    }


    protected function register(Request $request)
    {
        // validate the request
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // create the user
       $user = $this->users->create([
        'name' => $request->full_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
       ]);

        //create token for user
       $token = $user->createToken("Access Token")->plainTextToken;

        // return the token
        return ApiResponder::successResponse(
            "Registeration Successful",
            [
                "user" => new UserResource($user),
                "token" => $token,
            ],
            201
        );
    }

    protected function login(Request $request){
        // validate the request
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        //check email
        $user = $this->users->findByEmail($request->email);

        //check password
        if(!$user || !Hash::check($request->password,$user->password)){
            return ApiResponder::failureResponse("Invalid login credentials", 401);
        }

        //create token for user
       $token = $user->createToken("Access Token")->plainTextToken;

       // return the token
       return ApiResponder::successResponse(
           "Login Successful",
           [
               "user" => new UserResource($user),
               "token" => $token,
           ],
           201
       );

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return ApiResponder::successResponse("Logged out successfully");
    }


}
