<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthAPIController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        $errors = $validator->errors()->toArray();
        if ($validator->fails()) {
            return response()->json(array('errors' => $errors), 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        // $token = $user->createToken('nApp')->accessToken;
        $success = 'Success Registered Account!';

        return response()->json(['message' => $success], 200);
    }

    /**
     * Login API.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
    //     if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
    //         $user = Auth::user();
    //         $success['token'] = $user->createToken('appToken')->accessToken;
    //        //After successfull authentication, notice how I return json parameters
    //         return response()->json([
    //           'success' => true,
    //           'token' => $success,
    //           'user' => $user
    //       ]);
    //     } else {
    //    //if authentication is unsuccessfull, notice how I return json parameters
    //       return response()->json([
    //         'success' => false,
    //         'message' => 'Invalid Email or Password',
    //     ], 401);
    //     }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success = 'Success Login!';
            $token = $user->createToken($user->email)->accessToken;

        //    dd(auth()->user()->is_procurement);
            if (auth()->user()->is_procurement) {
                return response()->json(['access_token' => $token, 'message' => $success], 200);
            }
           else{
                return response()->json([
                    'access_token' => $token,
                    'message' => $success,
                    'user' => $user

                ], 200);
            }
        } else {
            return response()->json(['error' => 'Email or Password Invalid'], 401);
        }

    }

    /**
     * Change Password.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function changePassword(Request $request)
    {
        $user = User::find(auth('api')->user()->id);
        if (Hash::check($request->oldPassword, $user->password)) {
            $user->update(['password' => Hash::make($request->password)]);
            $response = [
                'status' => 'Success',
                'message' => 'Successfully Password Changed',
            ];
        } else {
            $response = [
                'status' => 'Fail',
                'message' => 'Password does not match',
            ];
        }

        return response()->json($response);
    }

    /**
     * Logout API.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
