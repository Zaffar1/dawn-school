<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        

        if(!Auth::check()){
          
            Redirect::setIntendedUrl($request->getUri());
            return redirect(route('user.login')) ;
        }else{
            $user = Auth::user();
            
            if($user->role_id != Role::USER){
                Redirect::setIntendedUrl($request->getUri());
                return redirect(route('user.login')) ;
            }
        }
       
        return $next($request);

    }
}
