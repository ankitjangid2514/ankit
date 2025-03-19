<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLogout
{
    public function handle(Request $request, Closure $next)
{
    // Check if the user is authenticated
    if (Auth::check()) {
        // Check the session lifetime
        if (session('lastActivity') && (time() - session('lastActivity') > 120 * 60)) { // 2 hours
            Auth::logout(); // Log the user out
            return redirect('/'); // Redirect to login page
        }
        // Update last activity time
        session(['lastActivity' => time()]);
    } else {
        // If the user is not authenticated, log them out and redirect to login page
        Auth::logout(); // Log the user out
        return redirect('/'); // Redirect to login page
    }

    return $next($request);
}

}
