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

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     description="Attempts to validate the user with a password. If so, sends back the user name and a personal token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="The email of the user that wants to use the API.",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="The password of the user that wants to use the API.",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A json containing the name of the user and a token. This token must be appended to the url of every request in this form: http://example.com/api/songs?token=[token].
     * The token is every time this request is done and it will last for 1 hour.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: invalid username or password.",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: invalid request.",
     *     ),
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     description="Removes the token for the current user. This route requires a valid token in the url",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="A valid and recent token.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A success message indicating that the token has been removed from the system, thus forcing the user to create a new one in order to kkep using the API.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response="400",
     *          ref="#/components/responses/expired"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          ref="#/components/responses/unauthorized"
     *     ),
     * )
     */
    public function logout()
    {

    }

    private function response(array $result, int $code)
    {
        return response()->json($result, $code);
    }

    private function getJwt(User $user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->email, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 // Expiration time
        ];

        return JWT::encode($payload, env('API_KEY'));
    }
}
