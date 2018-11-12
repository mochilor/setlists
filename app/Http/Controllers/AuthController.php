<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    private $request;

    const GENERIC_LOGIN_ERROR = 'Invalid user or password';

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function login()
    {
        try {
            $this->validate(
                $this->request,
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );
        } catch (ValidationException $e) {
            return $this->response(['error' => 'Invalid request'], 400);
        }

        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            return $this->response(['error' => self::GENERIC_LOGIN_ERROR], 401);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            $jwt = $this->getJwt($user);
            $user->token = $jwt;
            $user->save();

            return $this->response(
                [
                    'token' => $jwt,
                    'name' => $user->name,
                ],
                200
            );
        }

        return $this->response(['error' => self::GENERIC_LOGIN_ERROR], 401);
    }

    private function response(array $result, int $code)
    {
        return response()->json($result, $code);
    }

    private function getJwt(User $user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 // Expiration time
        ];

        return JWT::encode($payload, env('API_KEY'));
    }
}
