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

    public function __construct(IUser $users, Request $request){
        $this->users = $users;
        $request->headers->set('Accept', 'application/json');
		$request->headers->set('Content-Type', 'application/json');
    }

    /**
     * @OA\Post(
     * path="/api/v1/register",
     * summary="Register user",
     * description="Register by email, name, etc...",
     * operationId="register",
     * tags={"Athentication"},
     * @OA\RequestBody(
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="full_name", type="string", example="John Doe"),
     *       @OA\Property(property="email", type="string", format="email", example="johndoe@test.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *         @OA\Property(property="success", type="bolean", example=true),
     *         @OA\Property(property="message", type="string", example="Registeration Successful"),
     *         @OA\Property(property="data", type="null", example="null"),
     *      )
     *    ),
     * @OA\Response(
     *    response=422,
     *    description="Validation Errors",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="The given data was invalid"),
     *       @OA\Property(property="verification_errors", type="object", example={"email":{"The email field is required."}}),
     *      )
     *    )
     * )
     */
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

    /**
     * @OA\Post(
     * path="/api/v1/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="login",
     * tags={"Athentication"},
     * @OA\RequestBody(
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="johndoe@test.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Invalid login credentials"),
     *       @OA\Property(property="verification_errors", type="object", example=null),
     *      )
     *    ),
     * @OA\Response(
     *    response=422,
     *    description="Validation Errors",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="The given data was invalid"),
     *       @OA\Property(property="verification_errors", type="object", example={"email":{"The email field is required."}}),
     *      )
     *    )
     * )
     */
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

        // delete previous user tokens
        $user->tokens()->delete();


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

    /**
     * @OA\Post(
     * path="/api/v1/logout",
     * summary="Logout",
     * description="Logout user and invalidate token",
     * operationId="logout",
     * tags={"Athentication"},
     *  security={{"bearer_token":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *         @OA\Property(property="success", type="bolean", example=true),
     *         @OA\Property(property="message", type="string", example="Logged out successfully"),
     *         @OA\Property(property="data", type="null", example="null"),
     *      )
     *    ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="bolean", example=false),
     *       @OA\Property(property="message", type="string", example="You are not logged in"),
     *       @OA\Property(property="verification_errors", type="null", example="null"),
     *    )
     * )
     * )
     */
    public function logout(Request $request){
		
        auth()->user()->tokens()->delete();

        return ApiResponder::successResponse("Logged out successfully");
    }


}
