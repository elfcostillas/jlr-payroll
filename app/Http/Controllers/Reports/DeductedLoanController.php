<?php

namespace App\Http\Controllers\Reports;

use App\Excel\DeductedGovtLoan;
use App\Http\Controllers\Controller;
use App\Mappers\TimeKeepingMapper\PayrollPeriodMapper;
use App\Mappers\TimeKeepingMapper\PayrollPeriodWeeklyMapper;
use App\Repository\DeductedLoansRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;

class DeductedLoanController extends Controller
{
    //

    public $period;
    public $period_sg;
    public $repo;

    private $excel;

    public function __construct(
        PayrollPeriodMapper $period,
        PayrollPeriodWeeklyMapper $perid_sg,
        DeductedLoansRepository $repo,
        DeductedGovtLoan $excel
    ) {
        $this->period = $period;
        $this->period_sg = $perid_sg;
        $this->repo = $repo;
        $this->excel = $excel;
    }

    public function index()
    {
        $payroll_period = $this->period->listforPostedDropDown();
        $payroll_period_sg = $this->period_sg->listforPostedDropDown();
        $loan_types = DB::table('loan_types')
                        ->select(DB::raw("id, loan_code,description"))
                        ->get();

        $year = DB::table('payroll_period')
                ->select(DB::raw("DISTINCT YEAR(date_from) AS fy"))
                ->orderBy('fy','desc')
                ->get();

        return view('app.reports.deducted-loans.index', [
            'payroll_period' => $payroll_period,
            'payroll_period_sg' => $payroll_period_sg,
            'loan_types' => $loan_types,
            'fy_year' => $year
        ]);
    }

    public function download(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $loan_type = $request->loan_type;
        $emp_type = $request->emp_type;

        $label = DB::table('loan_types')
            ->where('loan_types.id','=', $request->loan_type)
            ->first();

        $array = [
            'month' => $month,
            'year' => $year,
            'loan_type' => $loan_type,
            'emp_type' => $emp_type,
        ];

        $periods = $this->periodFactory($array)->pluck('id');

        $deducted_loans = $this->repo->getDeductedLoans($periods,$array);
        
        // return view('app.reports.deducted-loans.export',[
        //     'label' => $label,
        //     'array' => $array,
        //     'data' => $deducted_loans
        // ]);

        $this->excel->setValues($label,$array,$deducted_loans);

        return Excel::download($this->excel,'DeductedGovtLoans.xlsx');
        
    }

    public function periodFactory($array)
    {
        switch($array['emp_type']) {
            case 'sg' :
                $periods = DB::table('payroll_period_weekly')
                ->whereRaw("MONTH(date_from) = ? ",[ $array['month']])
                ->whereRaw("YEAR(date_from) = ? ",[ $array['year']]);
                break;

            case 'confi' :
            case 'semi' :
                $periods = DB::table('payroll_period')
                ->whereRaw("MONTH(date_from) = ? ",[ $array['month']])
                ->whereRaw("YEAR(date_from) = ? ",[ $array['year']]);
                

                break;
        }

        return $periods;
    }
}


//SELECT id, loan_code,description FROM loan_types

