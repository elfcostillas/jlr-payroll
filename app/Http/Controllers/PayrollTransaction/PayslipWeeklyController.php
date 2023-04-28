<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mappers\PayrollTransaction\PayslipMapper;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;

class PayslipWeeklyController  extends Controller
{
    //
    private $payslip;
    private $employee;

    public function __construct(PayslipMapper $payslip,EmployeeMapper $employee)
    {
       $this->payslip = $payslip;
       $this->employee = $employee;
    
    }  

    public function index()
    {
        return view('app.payroll-transaction.payslip-weekly.index');
    }

    public function getPostedPeriods()
    {
        $result = $this->payslip->getWeeklyPosytedPeriod();

        return response()->json($result);
    }



    public function webView(Request $request)
    {
        $period_label = $this->payslip->getPeriodLabelWeekly($request->period);
        $result = $this->payslip->getDataWeekly($request->period,$request->div,$request->dept,$request->bio_id);
       
        return view('app.payroll-transaction.payslip.payslip-web',['data' => $result,'period_label' =>$period_label]);
    }
}
