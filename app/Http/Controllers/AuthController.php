<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|string',
                'password' => 'required|string'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()->all(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user != null) {
            $passwordMatch = Hash::check($password, $user->password);

            if ($passwordMatch) {

                Auth::login($user);
                $token = $user->createToken('token')->accessToken;
                return response()->json([
                    'user' => $user,
                    'token' => $token
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Invalid password'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } else {
            return response()->json([
                'error' => 'Invalid Email and Password'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function update(UserRequest $request)
    {
        $user = User::find(Auth::id());

        $userData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ];

        if (!empty($request->input('password'))) {
            $userData['password'] = bcrypt($request->input('password'));
        }

        $user->update($userData);

        return response()->json([
            'message' => 'Your details updated',
            'user' => $user,
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();

            $user->tokens->each(function (Token $token) {
                $token->revoke();
            });
            return response()->json(
                [
                    "message" => "User logged out successfully",
                    "success" => true
                ],
                Response::HTTP_OK
            );
        } else {
            return response()->json(['error' => 'Invalid session'], Response::HTTP_UNAUTHORIZED);
        }
    }
}
