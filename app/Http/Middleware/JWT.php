<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  bool  $optional
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $optional = false)
    {
        try {
            $token = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            if (!$optional) {
                if ($e instanceof TokenExpiredException) {
                    try {
                        $token = JWTAuth::refresh(JWTAuth::getToken());
                    } catch (JWTException $e) {
                        throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e);
                    }
                } else {
                    throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e);
                }
            }
        }

        if (!$token && !$optional) {
            throw new UnauthorizedHttpException('jwt-auth', 'Token not provided');
        }

        $request->attributes->set('token', $token);

        return $next($request);
    }
}
