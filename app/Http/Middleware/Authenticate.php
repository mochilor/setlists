<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use UnexpectedValueException;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (!env('AUTH')) {
            return $next($request);
        }

        $token = $request->get('token');
        if(!$token) {
            return $this->response(['error' => 'Unauthorized'], 401);
        }

        try {
            $credentials = JWT::decode($token, env('API_KEY'), ['HS256']);
        } catch(ExpiredException $e) {
            return $this->response(['error' => 'Provided token is expired'], 400);
        } catch(UnexpectedValueException $e) {
            return $this->response(['error' => 'Invalid token provided'], 400);
        } catch(Exception $e) {
            return $this->response(['error' => 'An error occurred while decoding token'], 500);
        }

        $user = User::find($credentials->sub); // Mejor habria que guardar en sub el email
        if (!$user) {
            return $this->response(['error' => 'Unauthorized'], 401);
        }

        if (!empty($user->token) && $user->token != $token) {
            return $this->response(['error' => 'Provided token is obsolete'], 400);
        }

        return $next($request);
    }

    private function response(array $result, int $code)
    {
        return response()->json($result, $code);
    }
}
