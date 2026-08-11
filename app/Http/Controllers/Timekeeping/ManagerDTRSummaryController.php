<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use App\Mappers\TimeKeepingMapper\PayrollPeriodMapper;
use Illuminate\Http\Request;

use App\Mappers\TimeKeepingMapper\ManagerDTRSummaryMapper;

class ManagerDTRSummaryController extends Controller
{
    public $period_obj;
    public $mapper;

    public function __construct(PayrollPeriodMapper $period,ManagerDTRSummaryMapper $mapper)
    {
        $this->period_obj = $period->currentPayrollPeriod();
        $this->mapper = $mapper;
        // $this->middleware('auth');
    }

    public function index() 
    {
       
        return view('app.timekeeping.manage-dtr-summary.index',['period_obj'=>$this->period_obj]);
    }

    public function employeeList(Request $request)
    {   

        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
            'search' => $request->input('search'),
            'emp_level' => $request->input('emp_level'),
            'pay_type' => $request->input('pay_type')
        ];

        $result = $this->mapper->employeeList($this->period_obj->id,$filter);

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $data = $request->models;

        foreach($data as $line)
        {
            $result = $this->mapper->updateValid($line);
        }
        return response()->json(true);
    }
}
