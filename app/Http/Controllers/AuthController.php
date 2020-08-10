<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Resources\TokenResource;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserSignupRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Allows the user to sign up
     *
     * @param UserSignupRequest $request
     */
    public function signup(UserSignupRequest $request): JsonResponse
    {
        $user = new User([
            'displayname' => $request->displayname,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->save();

        return response()->json(['message' => 'User successfully created.'], 201);
    }


    /**
     * Allows the user to log in
     *
     * @param UserLoginRequest $request
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $tokenResult = $request->user()->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return response()->json(new TokenResource($tokenResult), 200);
    }


    /**
     * Returns details of the currently logged in user
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        $response = new UserResource(Auth::user());

        return response()->json($response, 200);
    }


    /**
     * Allows the user to log out
     *
     * @param Request $request
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->token();

        if (false === !is_null($token)) {
            $token->revoke();
        }

        return response()->json(['message' => 'Successfully logged out.'], 200);
    }
}
