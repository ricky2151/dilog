<?php

namespace App\Http\Middleware;
use App\Exceptions\InvalidParameterException;

use Closure;

class FinanceUser
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
        if(auth('api')->user()->division_id == 1){
            return $next($request);
        }

        throw new InvalidParameterException(json_encode(["user"=>["just finance user can access this api"]]));
    }
}
