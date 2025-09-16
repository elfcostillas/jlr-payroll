<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccessRights
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$url)
    {
        $user = Auth::user();
        
        $result = DB::table('user_rights')->join('sub_menu','sub_menu_id','=','sub_menu.id')
        ->where('user_rights.user_id',$user->id)
        ->where('sub_menu_link',$url)
        ->count();

        if($result==0){
           
            return response()->json(['error'=>'Unauthorized access on this link.']);
        }

        return $next($request);
    }
}


/*SELECT COUNT(*) FROM user_rights INNER JOIN sub_menu ON sub_menu_id = sub_menu.id 
WHERE user_rights.user_id = 1 AND sub_menu_link = 'timekeeping/payroll-period';
*/