<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\LeaveCreditsMapper;

class LeaveCreditsController extends Controller
{
    //
    private $mapper;

    public function __construct(LeaveCreditsMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.leave-credits.index');
    }

    public function yearList()
    {
        $result =  $this->mapper->yearList();

        return response()->json($result);
    }

    public function empList(Request $request)
    {
        $year = $request->year;
        $result =  $this->mapper->empList($year);

        return response()->json($result);
    }

    public function save(Request $request)
    {
        $data = $request->models;

        foreach($data as $emp)
        {
            //dd($emp['line_id']);
            if($emp['biometric_id'] && $emp['fy_year']){
                if($emp['line_id']){
                    $this->mapper->updateValid($emp);
                } else {
                    $this->mapper->insertValid($emp);
                }
            }
        }
    }

    
}
