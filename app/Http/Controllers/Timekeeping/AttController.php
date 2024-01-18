<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use App\Mappers\TimeKeepingMapper\AttMapper;
use Illuminate\Http\Request;

class AttController extends Controller
{
    //
    private $mapper;

    public function __construct(AttMapper $mapper)
    {
        $this->mapper = $mapper;        
    }

    public function index() 
    {
        return view('app.timekeeping.att.index');
    }

    function download(Request $request)
    {
        // dd($request->from,$request->to);

        $result = $this->mapper->download($request->from,$request->to);

        return response()->json($result);
    }


}
