<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Errors\AuthenticationFailed;
use App\Exceptions\Errors\NoActiveSession;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    /**
     * Register a new user.
     *
     * Creates a new user account and returns the user data.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->updateOrCreate(
            ['email' => $request->email],
            $request->validated(),
        );

        return response()->json([
            'message' => __('errors.register_success'),
            'user' => UserResource::make($user),
        ], 201);
    }

    /**
     * Authenticate a user.
     *
     * Validates credentials and returns a Bearer token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw new AuthenticationFailed;
        }

        $token = $user->createToken('api-token')->accessToken;

        return response()->json([
            'message' => __('errors.login_success'),
            'token' => $token,
        ]);
    }

    /**
     * Logout the authenticated user.
     *
     * Revokes the current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->token();

        if (! $token) {
            throw new NoActiveSession;
        }

        $token->revoke();

        return response()->json(['message' => __('errors.logout_success')]);
    }

    /**
     * Get the authenticated user.
     *
     * Returns the profile of the currently authenticated user.
     */
    public function me(Request $request): UserResource
    {
        return UserResource::make($request->user());
    }
}
