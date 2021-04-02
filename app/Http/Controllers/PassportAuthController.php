<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @group  User management
 *
 * APIs for managing users
 */
class PassportAuthController extends Controller
{
    /**
     * Registration
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     *
     *
     * @bodyParam  name string required Name of the User.
     * @bodyParam  email string required Email of the User.
     * @bodyParam  password string required Password for the User.
     * @bodyParam  username string required Username for the User.
     * @bodyParam  profile_id int required The id of the profile. Example: 9
     *
     * @response  {
     *  "message": 'create success',
     * }
     *
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:4',
            'username' => 'required:min:2',
            'profile_id' => 'required|exists:profiles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data cannot be processed',
                'error' => $validator->errors(),
            ], 422);
        }

//        $profile = $request->profile;
//
//        if ($profile == 'board') {
//            $profile = User::ROLE_BOARD;
//        } else if ($profile == 'expert') {
//            $profile = User::ROLE_EXPERT;
//        } else if ($profile == 'trainer') {
//            $profile = User::ROLE_TRAINER;
//        } else if ($profile == 'competitor') {
//            $profile = User::ROLE_COMPETITOR;
//        } else if (!($profile == 1 || $profile == 2 || $profile == 3 || $profile == 4)) {
//            return response()->json(['message' => 'Data cannot be processed'], 422);
//        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'profile_id' => $request->profile_id,
            'skill_id' => $request->skill_id,
        ]);

        if ($user) {
            return response()->json(['message' => 'create success'], 200);
        } else {
            return response()->json(['message' => 'Data cannot be processed'], 422);
        }
    }

    /**
     * Login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam  username string required Username for the User.
     * @bodyParam  password string required Password for the User.
     *
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('User Token')->accessToken;
            $profileName = auth()->user()->profile->name;
            return response()->json(['token' => $token, 'profile' => $profileName], 200);
        } else {
            return response()->json(['message' => 'invalid login'], 401);
        }
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @response  {
     *  "message": 'create success',
     * }
     *
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        if (Auth::guard('api')->check()) {
            auth()->guard('api')->user()->token()->revoke();
            return response()->json(['message' => 'logout success'], 200);
        } else {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }
    }
}
