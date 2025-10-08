<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\PayrollTransaction\PayslipMapper;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use App\Mappers\PayrollTransaction\UnpostedPayrollRegisterMapper;

class PayslipConfiController extends Controller
{
    //
     private $payslip;
    private $employee;
    private $unposted;

    public function __construct(PayslipMapper $payslip,EmployeeMapper $employee,UnpostedPayrollRegisterMapper $unposted)
    {
       $this->payslip = $payslip;
       $this->employee = $employee;
       $this->unposted = $unposted;
    }   

    public function index()
    {
        return view('app.payroll-transaction.payslip-confi.index');
    }

    public function getPostedPeriods()
    {
        $result = $this->payslip->getConfiPostedPeriods();

        return response()->json($result);
    }

    public function getDivision()
    {

    }

    public function getDepartment()
    {

    }

    public function getEmployee()
    {

    }

    public function getEmployees(Request $request)
    {
        $result = $this->payslip->getConfiEmployees($request->period,$request->div,$request->dept);
        return response()->json($result);
    }

    public function webView(Request $request)
    {
        $period_label = $this->payslip->getPeriodLabel($request->period);
        $result = $this->payslip->getDataConfi($request->period,$request->div,$request->dept,$request->bio_id);
       
        return view('app.payroll-transaction.payslip.payslip-web',[
            'data' => $result,
            'period_label' =>$period_label
        ]);
    }

    public function print(Request $request)
    {

    }
}
