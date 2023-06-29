<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        if(Auth::check()){
            if(Auth::user()->role =='1'){
                return response()->json([
                    'message' => 'Connect success',
                    'name' =>Auth::user()->name,
                    'email' =>Auth::user()->email,
                    'role' => 'Admin'

                ], 403);
                // return $next($request);
            }else{
                // return redirect('/hello')->with('message','Access denied as you are not admin');
                return response()->json([
                    'message' => 'Connect success',
                    'name' =>Auth::user()->name,
                    'email' =>Auth::user()->email,
                    'role' => 'User'

                ], 403);
                
            }

        }else{
            // return redirect('/home')->with('message','ALogin to access');
            return response()->json([
                'message' => 'Connect success',
                'name' =>Auth::user()->name,
                'email' =>Auth::user()->email,
                'role' => 'User'

            ], 403);

        }
    }
}
