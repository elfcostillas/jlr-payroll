<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Mappers\Compensation\OtherIncomeWeeklyAppHeaderMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CanteenController extends Controller
{
    //

    private $mapper;

    public function __construct(OtherIncomeWeeklyAppHeaderMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        $payroll_period = DB::table('payrollregister_posted_s')
            ->join('payroll_period','payrollregister_posted_s.period_id','=','payroll_period.id')
            ->join('payroll_period_link','payroll_period.id','=','payroll_period_link.jlr_payroll_period')
            ->select(DB::raw("payroll_period.*,payroll_period_link.jlr_payroll_period,payroll_period_link.sg_payroll_period,concat(date_format(date_from,'%m/%d/%Y'),' - ',date_format(date_to,'%m/%d/%Y')) as label"))
            ->distinct()
            ->orderBy('payroll_period.id','DESC')
            ->get();

        return view('app.reports.canteen.index', [
            'payroll_periods' =>  $payroll_period
        ]);
    }

    public function print($id)
    {
        
        //SELECT jlr_payroll_period,sg_payroll_period FROM payroll_period_link WHERE jlr_payroll_period = 94;
        $payroll_periods = DB::table('payroll_period_link')
            ->join('payroll_period','payroll_period_link.jlr_payroll_period','=','payroll_period.id')
            ->where('jlr_payroll_period',$id)
            ->select('jlr_payroll_period','sg_payroll_period','date_from','date_to')
            ->first();

        $confi = $this->query($id,[1,2,3,4]);
        $rank_n_file = $this->query($id,[5]);
        $sg = $this->mapper->empList($payroll_periods->sg_payroll_period);

        $pdf = PDF::loadView('app.reports.canteen.print',[
            'confi' => $confi,
            'rank_n_file' => $rank_n_file,
            'sg' => $sg,
            'payroll_periods' => $payroll_periods
        ])->setPaper('letter','portrait');
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(40, 772, date_format(now(),"m/d/Y H:i:s"), null, 10, array(0, 0, 0));
        $canvas->page_text(510, 772, "Page {PAGE_NUM} of {PAGE_COUNT} ", null, 10, array(0, 0, 0));

        return $pdf->stream('DTR.pdf'); 
    }

    public function printCover($id)
    {
        $payroll_periods = DB::table('payroll_period_link')
            ->join('payroll_period','payroll_period_link.jlr_payroll_period','=','payroll_period.id')
            ->where('jlr_payroll_period',$id)
            ->select('jlr_payroll_period','sg_payroll_period','date_from','date_to')
            ->first();

        $confi = $this->query($id,[1,2,3,4]);
        $rank_n_file = $this->query($id,[5]);
        $sg = $this->mapper->empList($payroll_periods->sg_payroll_period);

        $total_counts_jlr = DB::table('payrollregister_posted_s')
            ->where('period_id', $payroll_periods->jlr_payroll_period)
            ->selectRaw("
                COUNT(CASE WHEN emp_level = 'confi' THEN 1 END) AS confi,
                COUNT(CASE WHEN emp_level = 'non-confi' THEN 1 END) AS non_confi,
                COUNT(*) AS total
            ")
            ->first();

        $total_counts_sg = DB::table('payrollregister_posted_weekly')
            ->where('period_id', $payroll_periods->sg_payroll_period)
            ->selectRaw("COUNT(*) AS total")
            ->first();

        $pdf = PDF::loadView('app.reports.canteen.print-cover',[
            'confi' => $confi,
            'rank_n_file' => $rank_n_file,
            'sg' => $sg,
            'payroll_periods' => $payroll_periods,
            'total_counts_jlr' => $total_counts_jlr,
            'total_counts_sg' => $total_counts_sg,
        ])->setPaper('letter','portrait');
        
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(40, 772, date_format(now(),"m/d/Y H:i:s"), null, 10, array(0, 0, 0));
        $canvas->page_text(510, 772, "Page {PAGE_NUM} of {PAGE_COUNT} ", null, 10, array(0, 0, 0));

        return $pdf->stream('DTR.pdf'); 
    }

    public function query($period_id,$emp_level)
    {
    
        return DB::table('deduction_onetime_headers')
            ->join('deduction_onetime_details','deduction_onetime_headers.id','=','deduction_onetime_details.header_id')
            ->join('employees','employees.biometric_id','=','deduction_onetime_details.biometric_id')
            ->select(DB::raw("deduction_onetime_details.*"))
            ->where('deduction_onetime_headers.period_id',$period_id)
            ->where('deduction_onetime_headers.deduction_type',8)
            ->whereIn('employees.emp_level',$emp_level)
            ->get();
    }
}


/*
SELECT deduction_onetime_details.* 
FROM deduction_onetime_headers 
INNER JOIN deduction_onetime_details ON deduction_onetime_headers.id = deduction_onetime_details.header_id
INNER JOIN employees ON employees.biometric_id = deduction_onetime_details.biometric_id
WHERE deduction_onetime_headers.period_id = 94 AND deduction_onetime_headers.deduction_type = 8
AND employees.emp_level IN (5);

SELECT posted_onetime_deductions.* 
FROM deduction_onetime_headers 
INNER JOIN posted_onetime_deductions ON deduction_onetime_headers.id = posted_onetime_deductions.deduction_id
INNER JOIN employees ON employees.biometric_id = posted_onetime_deductions.biometric_id
WHERE deduction_onetime_headers.period_id = 94 AND deduction_onetime_headers.deduction_type = 8
AND employees.emp_level IN (5);
*/