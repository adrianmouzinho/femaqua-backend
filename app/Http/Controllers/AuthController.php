<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     tags={"Auth"},
     *     summary="Create new user",
     *     description="Create a new user record",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "confirm_password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *             @OA\Property(property="confirm_password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Record created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid email",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Email already in use."),
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'Email already in use.'], 400);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];

        $user = User::create($data);

        return response()->json(new UserResource($user), 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"Auth"},
     *     summary="Authenticate user",
     *     description="Authenticate a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="token_value"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials."),
     *         )
     *     )
     * )
     */
    public function login(LoginUserRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('API Token');

            return response()->json([
                'token' => $token->plainTextToken,
            ]);
        }
        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     tags={"Auth"},
     *     summary="Logout user",
     *     description="Logout a user",
     *     @OA\Response(
     *         response=200,
     *         description="Unauthenticated successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
