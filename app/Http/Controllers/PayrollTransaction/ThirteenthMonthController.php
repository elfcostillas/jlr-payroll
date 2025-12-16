<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Excel\ThirteenthMonthSG;
use App\Http\Controllers\Controller;
use App\Mappers\PayrollTransaction\ThirteenthMonthMapper;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use  App\Excel\BankTransmittal;
use  App\Excel\ThirteenthMonthConso;
use Barryvdh\DomPDF\Facade\Pdf;

class ThirteenthMonthController extends Controller
{
    //
    private $mapper;
    private $excel;
    private $rcbc;
    private $conso;

    public function __construct(ThirteenthMonthMapper $mapper,ThirteenthMonthSG $excel,BankTransmittal $rcbc,ThirteenthMonthConso $conso)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
        $this->rcbc = $rcbc;
        $this->conso = $conso;
    }

    public function index()
    {
        $years =  $this->mapper->getYears();

        return view('app.payroll-transaction.thirteenth-month-weekly.index',['years' => $years]);
    }

    public function showTable(Request $request)
    {
        $result = $this->mapper->buildData($request->year);

       return view("app.payroll-transaction.thirteenth-month-weekly.table",['result' => $result['location'],'payroll_period' => $result['payroll_period'] ]);
    }

    public function insertOrUpdate(Request $request)
    {
        $keys = explode('|',$request->id);

        $this->mapper->insertOrUpdate($keys,$request->val);

        return response()->json($keys);
        // return response()->json($request->id,$request->val);
    }

    public function download(Request $request)
    {

        $result = $this->mapper->buildData($request->year);

        $this->excel->setValues($result);
        return Excel::download($this->excel,"ThirteenthMonthSG{$request->year}.xlsx");
    }

    public function post(Request $request)
    {
        $year = $request->cyear;

        if(!$this->mapper->isPosted($year)){
            $result = $this->mapper->post13thMonth($year);
        }else{
            return response()->json(['error'=>"Year $year is already posted."])->setStatusCode(500);
        }

        return response()->json(['success'=>"13th Month of year $year was successfully posted."]);

       
    }

    public function bank_transmittal(Request $request)
    {
        // $result = $this->posted->getPostedDataforRCBC($request->period_id);
        $year = $request->year;

        $result = $this->mapper->getPosted($year);
      

        // // dd($result);

        $this->rcbc->setValues($result);
        return Excel::download($this->rcbc,"ThirteenthMonthBankTransmittal_SG_$year.xlsx");
    }

    public function conso_bank_transmittal(Request $request)
    {
        // $result = $this->posted->getPostedDataforRCBC($request->period_id);
        $year = $request->year;

        $result = $this->mapper->getConsoPosted($year);
      

        // // dd($result);

        $this->rcbc->setValues($result);
        return Excel::download($this->rcbc,"ThirteenthMonthBankTransmittalConso_SG_$year.xlsx");
    }

    public function print(Request $request)
    {
        $year = $request->year;
        $location = $request->location;

        $loc_name = $this->mapper->getLocation($location);

        $result = $this->mapper->getNetpay($year,$location);

        $period = $this->mapper->getRange($year);

        $pdf = PDF::loadView('app.payroll-transaction.thirteenth-month-weekly.print',['data'=> $result,'period' => $period])->setPaper('letter','portrait');
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(40, 758, date_format(now(),"m/d/Y H:i:s") ." - $loc_name ", null, 10, array(0, 0, 0));
        $canvas->page_text(510, 758, "Page {PAGE_NUM} of {PAGE_COUNT} ", null, 10, array(0, 0, 0));

        return $pdf->stream('DTR.pdf'); 

        // return view('app.payroll-transaction.thirteenth-month-weekly.print',['data'=> $result]);
    }

    public function conso(Request $request)
    {
        $year = $request->year;

        $months = [
            12 => 'DECEMBER',
            1 => 'JANUARY',
            2 => 'FEBRUARY',
            3 => 'MARCH',
            4 => 'APRIL',
            5 => 'MAY',
            6 => 'JUNE',
            7 => 'JULY',
            8 => 'AUGUST',
            9 => 'SEPTEMBER',
            10 => 'OCTOBER',
            11 => 'NOVEMBER',
        ];

        $semi = $this->mapper->buildSemiMonthly($months,$year);
        $weekly = $this->mapper->buildWeekly($months,$year);
        // $weekly_sub = $this->mapper->buildWeeklySub($months,$year);

        $this->conso->setValues($semi,$weekly,$months);
        return Excel::download($this->conso,"ThirteenthMonthConso_$year.xlsx");
        
        // return view("app.payroll-transaction.thirteenth-month-weekly.conso-v2",[
        //     'semi' => $semi,
        //     'weekly' => $weekly ,
        //     'months' => $months ,
        // ]);

        // return view('app.payroll-transaction.thirteenth-month-weekly.conso',['semi' => $semi,'weekly' => $weekly,'months' => $months ]);
    }

    public function downloadInActive(Request $request)
    {
        $result = $this->mapper->buildDataInActive($request->year);
       
        $this->excel->setValues($result);
        return Excel::download($this->excel,"ThirteenthMonthSG{$request->year}.xlsx");
    }


    public function downloadInActiveConso(Request $request)
    {
        $year = $request->year;

        $months = [
            12 => 'DECEMBER',
            1 => 'JANUARY',
            2 => 'FEBRUARY',
            3 => 'MARCH',
            4 => 'APRIL',
            5 => 'MAY',
            6 => 'JUNE',
            7 => 'JULY',
            8 => 'AUGUST',
            9 => 'SEPTEMBER',
            10 => 'OCTOBER',
            11 => 'NOVEMBER',
        ];

        $semi = $this->mapper->buildSemiMonthlyI($months,$year);
        $weekly = $this->mapper->buildWeeklyI($months,$year);

        $this->conso->setValues($semi,$weekly,$months);
        return Excel::download($this->conso,"ThirteenthMonthConso_$year.xlsx");
        

    }


}
