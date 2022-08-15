<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class AuthMiddleware
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

        if(!$access_token){
            return response()->json(["error" => "Pagina proibida"]);
        }

        $user_with_token = User::where("token",$access_token)->first();

        if(!$user_with_token){
            return response()->json(["error" => "Pagina proibida"]);
        }

        if($user_with_token->user_id != base64_decode(Crypt::decrypt($access_token))){
            return response()->json(["error" => "Pagina proibida"]);
        }
        

        return $next($request);
    }
}
