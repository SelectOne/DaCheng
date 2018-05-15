<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;

class MemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $rs = Admin::find(session('admin_id'));
        if ( ! $rs->can("member-*") || ! $rs->hasRole("owner") ) {
            abort(403);
        }
        return $next($request);
    }
}
