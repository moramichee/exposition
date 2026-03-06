<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class EnsureAdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (HttpResponse)  $next
     */
    public function handle(Request $request, Closure $next): HttpResponse
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (! Auth::user()?->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Acces reserve a un administrateur.']);
        }

        return $next($request);
    }
}
