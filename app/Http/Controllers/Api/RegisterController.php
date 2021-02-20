<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\BaseApiController as BaseApiController;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Validator;

class RegisterController extends BaseApiController
{


    public function register(Request $request)
    {
        $exist = User::where('email', $request->email)->first();
        if (!$exist) {
            $validator = $this->validateUser($request);

            if ($validator->fails()) {
                return $this->sendError(
                    'Validation Error.', $validator->errors()
                );
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            return $this->sendResponse($this->generateToken($user), 'User registered successfully!'
            );

        }

        return $this->sendError(
            'this User is existed in system.');


    }//end register function


//    public function login(Request $request)
//    {
//        $attempt = $this->attemptUser($request);
//
//
//        if ($attempt) {
//
//            $user = Auth::user();
//            $success = $this->generateToken($user);
//            return $this->sendResponse($success, 'User logged in successfully!');
//
//        } else {
//
//            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized']);
//
//        }
//    }

    public function login(Request $request)
    {
        $exist = User::where('email', $request->email)->first();

        if (Hash::check($request->password, $exist['password'])) {
            Auth::login($exist);
            $user = User::find($exist->id);

            $data = $user;
            $data['token'] = $user->createToken('MyApp')->accessToken;


            return $this->sendResponse($data, 'User logged in successfully!');
        } else {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized'], 401);


        }

    }




    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['success' =>'logout_success'],200);
        }else{
            return response()->json(['error' =>'api.something_went_wrong'], 500);
        }
    }


    ///////////////////////////////////////////////////////////////////////////////

    public function generateToken($user)
    {
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return $success;
    }


    public function validateUser(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]
        );
    }

    public function attemptUser(Request $request)
    {
        return Auth::attempt(
            [
                'email' => $request->email,
                'password' => Hash::check($request->password)
            ]
        );
    }


} //end registerController
