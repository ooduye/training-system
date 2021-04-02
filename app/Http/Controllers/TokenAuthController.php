<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use phpseclib3\Crypt\Hash;

class TokenAuthController extends Controller
{
    /**
     * Registration
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [
            'username' => $request->username,
            'password' => $request->password
        ];

        $user = User::checkIfLoginValid($data['email'], $data['password']);
        if (is_object($user) && get_class($user) === 'App\Models\User') {
            $token = $this->createToken($user);
            $profileName = $user->profile->name;
            return response()->json(['token' => $token, 'profile' => $profileName], 200);
        } else {
            return response()->json(['message' => 'invalid login'], 401);
        }
    }

    /**
     * Logout
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        if (Auth::guard('api')->check()) {
            $user = auth()->guard('api')->user();
            $user->token = '';
            $user->save();
            return response()->json(['message' => 'logout success'], 200);
        } else {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }
    }

    /**
     * Create the authenticated user's API token.
     * @param $user
     * @return string
     */
    public function createToken($user): string
    {
        $token = Str::random(60);

        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return $token;
    }
}
