<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mappers\PayrollTransaction\PayslipMapper;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use App\Mappers\PayrollTransaction\PostedPayrollRegisterWeeklyMapper;

class PayslipWeeklyController  extends Controller
{
    //
    private $payslip;
    private $employee;
    private $posted;

    public function __construct(PayslipMapper $payslip,EmployeeMapper $employee,PostedPayrollRegisterWeeklyMapper $posted)
    {
       $this->payslip = $payslip;
       $this->employee = $employee;
       $this->posted = $posted;
    
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
      
        return view('app.payroll-transaction.payslip-weekly.payslip-web',['data' => $result,'period_label' =>$period_label]);
    }

    public function dtrSummary(Request $request)
    {
        $label = [];
        $headers = $this->posted->getHeaders($request->period)->toArray();
        $colHeaders = $this->posted->getColHeaders();
       
        $result = $this->posted->getData($request->period);

        foreach($headers as $key => $value){
          
            if($value==0){
                unset($headers[$key]);
            }
        }

        foreach($colHeaders  as  $value ){
            $label[$value->var_name] = $value->col_label;
        }

        return view('app.payroll-transaction.payslip-weekly.dtr-summary',['data'=> $result,'headers'=>$headers,'label' => $label]);
    }
}
