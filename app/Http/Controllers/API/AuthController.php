<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use GeneralTrait;

    public function __construct()
    {
      $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    public function register(Request $request)
    {

        try {
            $rules = [
                'name'              =>      'required|string|max:40|min:6',
                'email'             =>      'required|email|unique:users,email',
                'password'          =>      'required|min:6',
                'confirm_password'  =>      'required|same:password',

            ];


           //Validate data
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }


        //Request is valid, create new user
         $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
        	'password' => app('hash')->make($request->password)

        ]);

        //if User not created, return error response

        if (!$user){
            return $this->returnError('E005', 'created user failed');

        }else{
    //generate token
    $token = Auth::login($user);
    $user ->api_token = $token;
    return $this->returnData('user', $user,'Register Successfully');  //return json response

        }



} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}



//=========================login
    public function login(Request $request)
    {

        try {

            //validate
            $rules = [
                "email" => "required",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            //login

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('api')->attempt($credentials);  //generate token

            if (!$token)
                return $this->returnError('E001', 'E-mail and password not match');


           //return token with data
            $user = Auth::guard('api')->user();
            $user ->api_token = $token;
            return $this->returnData('user', $user);  //return json response

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


//=====================logout
    public function logout(Request $request)
    {
         $token = $request -> header('auth-token');
        if($token){
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError('E002','some thing went wrongs');
            }
            return $this->returnSuccessMessage('Logged out successfully',200);
        }else{
            $this -> returnError('E002','some thing went wrongs');
        }

    }
}
