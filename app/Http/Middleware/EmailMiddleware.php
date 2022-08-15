<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class EmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $access_token = $request->header("access_token");

        $user = User::where("user_id",base64_decode(Crypt::decrypt($access_token)))->first();

        //checking if user email was verified
        if(!$user->user_email_verified){
            return response()->json(["message" => "Por favor verifique o seu email"]);
        }

        return $next($request);
    }
}
