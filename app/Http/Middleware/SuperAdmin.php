<?php

namespace App\Http\Middleware;

use Closure;

class SuperAdmin
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
        if( file_exists(storage_path('installed'))){
            if(!super_access()){
                if($request->ajax()){
                    $result['msg'] = 'warning';
                    $result['message'] = __("You do not have enough permissions to perform requested operation.");
                    return response()->json($result);
                }
                session()->flash('global', __("You do not have enough permissions to perform requested operation."));
                return redirect()->route('admin.home')->with(['global' => __("You do not have enough permissions to perform requested operation.")]);
            }
        }
        return $next($request);
    }
}
