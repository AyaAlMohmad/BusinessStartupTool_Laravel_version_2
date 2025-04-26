<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (auth()->user()->is_admin == 1) {
                return $next($request); // Proceed if user is an admin
            } else {
                return redirect()->url('https://businesstools.valuenationapp.com'); // Redirect non-admin users to home or another route
            }
        }

        return redirect('/login'); // Redirect unauthenticated users to login
    }
}
