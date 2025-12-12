<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use App\Mappers\PayrollTransaction\ThirteenthMonthMapper;
use Illuminate\Http\Request;
use App\Excel\ThirteenthMonthConfi;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\BankTransmittal;
use App\Excel\ThirteenthMonthMonthly;
use Barryvdh\DomPDF\Facade\Pdf;

class ThirteenthMonthJLRController extends Controller
{
    //
    private $mapper;
    private $excel;
    private $excel2;
    private $rcbc;

    public function __construct(ThirteenthMonthMapper $mapper,ThirteenthMonthConfi $excel, ThirteenthMonthMonthly $excel2,BankTransmittal $rcbc)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
        $this->excel2 = $excel2;
        $this->rcbc = $rcbc;
    }

    public function index_confi(Request $request)
    {
        $years =  $this->mapper->getYears();
        $months = array(
            ['label' => 'December - April','value' => 1],
            ['label' => 'May - November','value' => 2]
        );

        return view('app.payroll-transaction.thirteenth-month-confi.index_confi',[
            'years' => $years,
            'months' => $months,
        ]);
    }

    public function showTable(Request $request)
    {
        // dd($request->year,$request->month);
        $result = $this->mapper->buildDataJLRConfi($request->year,$request->month);

        return view("app.payroll-transaction.thirteenth-month-confi.table",['data' => $result]);
    }

    public function index_rankAndFile(Request $request)
    {
        
    }

    public function insertOrUpdate(Request $request)
    {
        $keys = explode('|',$request->id);

        $result = $this->mapper->insertOrUpdateJLR($keys,$request->val);

        return response()->json($result);

    }

    public function download(Request $request)
    {
        $result = $this->mapper->buildDataJLRConfi($request->year,$request->month);
        $this->excel->setValues($result);

        return Excel::download($this->excel,"ThirteenthMonthConfi{$request->year}.xlsx");
    }

    public function bank_transmittal(Request $request)
    {
        /*
        $year = $request->year;

        $result = $this->mapper->getPosted($year);
      

        // // dd($result);

        $this->rcbc->setValues($result);
        return Excel::download($this->rcbc,"ThirteenthMonthBankTransmittal_SG_$year.xlsx");
        */

        $year = $request->year;
        $month = $request->month;

        $result = $this->mapper->getPostedJLR($year,$month,'confi');

        $this->rcbc->setValues($result);
        return Excel::download($this->rcbc,"ThirteenthMonthBankTransmittal_JLR_$year.'_'.$month.xlsx");

    }

    public function post(Request $request)
    {
        $year = $request->cyear;
        $month = $request->cmonth;

        if(!$this->mapper->isPostedJLR($year,$month,'confi')){
            $result = $this->mapper->post13thMonthJLR($year,$month,'confi');
        }else{
            $str = ($month == 1) ? '1sf half' : '2nd half';

            return response()->json(['error'=>"Year $year $str is already posted."])->setStatusCode(500);
        }

        return response()->json(['success'=>"13th Month of year $year was successfully posted."]);

    }

    public function print(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        $result = $this->mapper->getNetpayJLR($year,$month,'confi');

        $period = ($month == 1) ? 'December '.$year.' - April '.$year : 'May '.$year.'  - November '.$year; 

        $pdf = PDF::loadView('app.payroll-transaction.thirteenth-month-confi.print',['data'=> $result,'period' => $period])->setPaper('letter','portrait');
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(40, 758, date_format(now(),"m/d/Y H:i:s"), null, 10, array(0, 0, 0));
        $canvas->page_text(510, 758, "Page {PAGE_NUM} of {PAGE_COUNT} ", null, 10, array(0, 0, 0));

        return $pdf->stream('DTR.pdf'); 

    }

    public function downloadInactive(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        $result = $this->mapper->buildDataJLRConfiInActive($request->year,$request->month);
        $this->excel->setValues($result);

        return Excel::download($this->excel,"ThirteenthMonthConfi{$request->year}.xlsx");

    }

    public function conso(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        if($month == 1){
            $months = [
                12 => 'DECEMBER',
                1 => 'JANUARY',
                2 => 'FEBRUARY',
                3 => 'MARCH',
                4 => 'APRIL',
              
            ];
        }else{
            $months = [

                5 => 'MAY',
                6 => 'JUNE',
                7 => 'JULY',
                8 => 'AUGUST',
                9 => 'SEPTEMBER',
                10 => 'OCTOBER',
                11 => 'NOVEMBER',
            ];
        }

        $semi = $this->mapper->buildSemiMonthlyJLR($months,$month,$year);
       
        $this->excel2->setValues($semi,$months);
        return Excel::download($this->excel2,"ThirteenthMonthConfiMonthly{$request->year}.xlsx");

        // dd($semi['employees']);

        // return view("app.payroll-transaction.thirteenth-month-confi.conso",[
        //     'semi' => $semi,
        //     'months' => $months ,
        // ]);

    }
}
