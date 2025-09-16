<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BiometricController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index()
    {
        return view('app.accounts.biometric.index');
    }

    public function save(Request $request)
    {
     
        $user = Auth::user();

        $result = DB::table('users')
        ->where('id',$user->id)
        ->update(['biometric_id'=>$request->biometric_id]);

        return response()->json($result);
    }
    
    public function getID(Request $request)
    {
        $user = Auth::user();

        $result = DB::table('users')
        ->select('biometric_id')
        ->where('id',$user->id)
        ->first();

        return response()->json($result);
    }
}
