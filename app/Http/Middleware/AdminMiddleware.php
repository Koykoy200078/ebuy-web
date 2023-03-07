<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    const ADMIN_ROLE = 2;

    protected $redirectUrl = '/home';
    protected $errorCode = 403;

    public function __construct($redirectUrl = null, $errorCode = null)
    {
        if ($redirectUrl) {
            $this->redirectUrl = $redirectUrl;
        }

        if ($errorCode) {
            $this->errorCode = $errorCode;
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role_as < self::ADMIN_ROLE) {
            return redirect()->to($this->redirectUrl)->with('error', 'Access Denied. You are not an admin.');
        }

        return $next($request);
    }
}
