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
        $path = $request->path();

        if(!$token) {
            return $this->response(['error' => 'Unauthorized'], 401);
        }

        try {
            $credentials = JWT::decode($token, env('API_KEY'), ['HS256']);
        } catch(ExpiredException $e) {
            return $this->response(['error' => 'Provided token has expired'], 400);
        } catch(UnexpectedValueException $e) {
            return $this->response(['error' => 'Invalid token provided'], 400);
        } catch(Exception $e) {
            return $this->response(['error' => 'An error occurred while decoding token'], 500);
        }

        $user = User::where('email', $credentials->sub)->first();
        if (!$user) {
            return $this->response(['error' => 'Unauthorized'], 401);
        }

        if ($user->token != $token) {
            return $this->response(['error' => 'Provided token is obsolete'], 400);
        }

        if (strstr($path, 'logout') !== false) {
            $user->token = null;
            $user->save();

            return $this->response(['result' => 'Token was reset'], 200);
        }

        return $next($request);
    }

    private function response(array $result, int $code)
    {
        return response()->json($result, $code);
    }
}
