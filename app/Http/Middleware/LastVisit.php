<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class LastVisit
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getMethod() == Request::METHOD_GET) {
            User::where("id", \Auth::user()->id ?? null)->update(["last_visited_link" => json_encode(url()->full())]);
        }

        return $next($request);
    }

}
