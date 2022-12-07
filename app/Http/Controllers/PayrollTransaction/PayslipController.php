<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\PayrollTransaction\PayslipMapper;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use App\Mappers\PayrollTransaction\UnpostedPayrollRegisterMapper;

class PayslipController extends Controller
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
        return view('app.payroll-transaction.payslip.index');
    }

    public function getPostedPeriods()
    {
        $result = $this->payslip->getPostedPeriods();

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
        
        $result = $this->payslip->getEmployees($request->period,$request->div,$request->dept);

        return response()->json($result);
    }

    public function webView(Request $request)
    {
        $period_label = $this->payslip->getPeriodLabel($request->period);
        $result = $this->payslip->getData($request->period,$request->div,$request->dept,$request->bio_id);
        //$headers = $this->unposted->getHeaders($request->period);

        // foreach($header as $h)
        // {

        // }
        //dd($period_label->date_range);
        return view('app.payroll-transaction.payslip.payslip-web',['data' => $result,'period_label' =>$period_label]);
    }

    public function print(Request $request)
    {

    }


    //SELECT period_id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS date_range FROM posting_info INNER JOIN payroll_period ON payroll_period.id = posting_info.period_id ORDER BY period_id DESC ;

}
