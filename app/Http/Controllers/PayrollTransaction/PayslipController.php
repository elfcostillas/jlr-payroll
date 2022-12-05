<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\PayrollTransaction\PayslipMapper;

class PayslipController extends Controller
{
    //
    private $payslip;

    public function __construct(PayslipMapper $payslip)
    {
       $this->$payslip = $payslip;
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

    //SELECT period_id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS date_range FROM posting_info INNER JOIN payroll_period ON payroll_period.id = posting_info.period_id ORDER BY period_id DESC ;

}
