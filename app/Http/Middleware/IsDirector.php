<?php

namespace App\Http\Middleware;

use App\Models\web\AcnMember;
use Closure;

class IsDirector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $isDirector = AcnMember::isUserDirector(auth()->user()->MEM_NUM_MEMBER);

        if (!$isDirector) {
            return redirect('/welcome');
        }

        return $next($request);
    }
}
