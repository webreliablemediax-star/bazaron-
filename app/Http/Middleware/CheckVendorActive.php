<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckVendorActive
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();

            if (
                ($user->user_type == 'vendor' || $user->user_type == 'vendor_staff')
                && $user->is_active == 0
            ) {
                Auth::logout();

                return redirect('/login')
                    ->with('error', 'Your account has been disabled by admin.');
            }
        }

        return $next($request);
    }
}