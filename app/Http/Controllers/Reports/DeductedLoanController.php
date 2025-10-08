<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Mappers\TimeKeepingMapper\PayrollPeriodMapper;
use App\Mappers\TimeKeepingMapper\PayrollPeriodWeeklyMapper;
use Illuminate\Http\Request;

class DeductedLoanController extends Controller
{
    //

    public $period;
    public $period_sg;

    public function __construct(
        PayrollPeriodMapper $period,
        PayrollPeriodWeeklyMapper $perid_sg

    ) {
        $this->period = $period;
        $this->period_sg = $perid_sg;
    }

    public function index()
    {
        $payroll_period = $this->period->listforPostedDropDown();
        $payroll_period_sg = $this->period_sg->listforPostedDropDown();
        $loan_types = null;

        dd($payroll_period);

        return view('app.reports.deducted-loans.index', [
            'payroll_period' => $payroll_period,
            'payroll_period_sg' => $payroll_period_sg,
            'loan_types' => $loan_types,
        ]);
    }
}
