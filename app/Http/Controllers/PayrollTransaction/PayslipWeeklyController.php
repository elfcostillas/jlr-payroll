<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mappers\PayrollTransaction\PayslipMapper;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use App\Mappers\PayrollTransaction\PostedPayrollRegisterWeeklyMapper;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mappers\TimeKeepingMapper\PayrollPeriodWeeklyMapper;

class PayslipWeeklyController  extends Controller
{
    //
    private $payslip;
    private $employee;
    private $posted;
    private $period;

    public function __construct(PayslipMapper $payslip,EmployeeMapper $employee,PostedPayrollRegisterWeeklyMapper $posted,PayrollPeriodWeeklyMapper $period)
    {
       $this->payslip = $payslip;
       $this->employee = $employee;
       $this->posted = $posted;
       $this->period = $period;
    
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
        $result = $this->payslip->getDataWeekly($request->period,$request->div,$request->dept,$request->bio_id,$request->loc);
      
        return view('app.payroll-transaction.payslip-weekly.payslip-web',['data' => $result,'period_label' =>$period_label]);
    }

    public function dtrSummary(Request $request)
    {
        $label = [];
        $headers = $this->posted->getHeaders($request->period)->toArray();
        $colHeaders = $this->posted->getColHeaders();

        $location = $this->posted->getLocation($request->loc);
       
        $result = $this->posted->getData($request->loc,$request->period,$request->div,$request->dept,$request->bio_id);
        // $result = $this->posted->getData($request->period);

        $period_label = $this->period->makeRange($request->period);

        foreach($headers as $key => $value){
          
            if($value==0){
                unset($headers[$key]);
            }
        }

        foreach($colHeaders  as  $value ){
            $label[$value->var_name] = $value->col_label;
        }

        
    
        // return view('app.payroll-transaction.payslip-weekly.dtr-summary',['data'=> $result,'headers'=>$headers,'label' => $label]);

        $pdf = PDF::loadView('app.payroll-transaction.payslip-weekly.dtr-summary-pdf',['data'=> $result,'headers'=>$headers,'label' => $label,'period_label' => $period_label])->setPaper('A4','portrait');
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(40, 812, date_format(now(),"m/d/Y H:i:s") ." - $location ", null, 10, array(0, 0, 0));
        $canvas->page_text(510, 812, "Page {PAGE_NUM} of {PAGE_COUNT} ", null, 10, array(0, 0, 0));

        return $pdf->stream('DTR.pdf'); 
    }

    /*
    public function pdfView(Request $request)
    {   
        $period_label = $this->payslip->getPeriodLabelWeekly($request->period);

        $result = $this->payslip->getDataWeekly($request->period,$request->div,$request->dept,$request->bio_id,$request->loc);
      
        // return view('app.payroll-transaction.payslip-weekly.payslip-web',['data' => $result,'period_label' =>$period_label]);
        $pdf = PDF::loadView('app.payroll-transaction.payslip-weekly.payslip-pdf',['data' => $result,'period_label' =>$period_label])->setPaper('A4','portrait');

        $location = $this->posted->getLocation($request->loc);
        
        $pdf->output();
        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();

        // $canvas = $pdf->get_canvas();
        // $canvas->page_text(510, 812, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

        // $dom_pdf = $pdf->getDomPDF();
        // // dd($pdf);
        // $canvas = $dom_pdf->get_canvas();
       
        // $canvas->page_text(510, 812, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 9, array(0, 0, 0));
        // $pdf->output();

        // $pdf = PDF::loadView($this->dir.'.print',['header'=>$printHeader,'suppliers'=> $suppliers,'detail' => $printDetail,'mr_no' => $mr_no])->setPaper('A4','portrait');
        $dom_pdf = $pdf->getDomPDF();
        //$pdf->output();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(40, 812, date_format(now(),"m/d/Y H:i:s")." - $location ", null, 10, array(0, 0, 0));
        $canvas->page_text(510, 812, "Page {PAGE_NUM} of {PAGE_COUNT} ", null, 10, array(0, 0, 0));

        return $pdf->stream('Payslip.pdf'); 
    }
    */

    public function pdfView(Request $request)
    {
        $period_label = $this->payslip->getPeriodLabelWeekly($request->period);
        $result = $this->payslip->getDataSG($request->period,$request->div,$request->dept,$request->bio_id);
    
      
        return view('app.payroll-transaction.payslip.payslip-web-sg',[
            'data' => $result,
            'period_label' =>$period_label
        ]);
    }
}
