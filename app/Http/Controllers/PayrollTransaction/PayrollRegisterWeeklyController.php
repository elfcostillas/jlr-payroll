<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use Illuminate\Support\Facades\Auth;
use App\Mappers\TimeKeepingMapper\PayrollPeriodWeeklyMapper;
use App\Mappers\PayrollTransaction\UnpostedPayrollRegisterWeeklyMapper;
use App\Excel\UnpostedPayrollRegisterWeekly;
use Maatwebsite\Excel\Facades\Excel;

class PayrollRegisterWeeklyController extends Controller
{
    //

    private $employee;
    private $period;
    private $mapper;

    public function __construct(EmployeeMapper $employee,PayrollPeriodWeeklyMapper $period,UnpostedPayrollRegisterWeeklyMapper $mapper,UnpostedPayrollRegisterWeekly $excel)
    {
        $this->employee = $employee;
        $this->period = $period;
        $this->mapper = $mapper;
        $this->excel = $excel;
    }

    public function index()
    {
        return view('app.payroll-transaction.payroll-register-weekly.index');
    }

    public function getUnpostedPeriod()
    {
        $user = Auth::user();
        $result = $this->period->listforDropDown();
        // if($user->biometric_id!="" && $user->biometric_id!=0 && $user->biometric_id != null){
        //     $position = $this->employee->getPosition($user->biometric_id);

        //     $result = $this->unposted->unpostedPeriodList('semi',$position);

        //     return response()->json($result);
        // }
        return response()->json($result);
    }

    public function compute(Request $request)
    {
        $period = $request->id;

        $result = $this->mapper->compute($period);
       
        $data = $this->mapper->showComputed($period);

        return view('app.payroll-transaction.payroll-register-weekly.payroll-register',['data' => $data]);
    }

    public function downloadExcelUnposted(Request $request)
    {
        $period = $request->id;

        $label = '';
        $data = $this->mapper->showComputed($period);

        $this->excel->setValues($data,$label);
        return Excel::download($this->excel,'PayrollRegisterWeekly'.$period.'.xlsx');
        
    }

    public function postPayroll(Request $request)
    {
    
        $result = $this->mapper->postPayroll($request->period_id);


        return response()->json($result);
    }

    
}
