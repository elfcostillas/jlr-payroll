<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\PayrollTransaction\PostedPayrollRegisterMapper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use  App\Excel\BankTransmittal;

class BankTransmittalController extends Controller
{
    //
    private $posted;
    private $excel;

    public function __construct(PostedPayrollRegisterMapper $posted,BankTransmittal $excel)
    {
        $this->posted = $posted;
        $this->excel = $excel;
    }

    public function index()
    {
        return view('app.payroll-transaction.bank-transmittal.index');
    }

    public function postedPeriods()
    {
        $user = Auth::user();
        //dd($user->toArray());
        $results = $this->posted->getPostedPeriods($user);

        if(is_string($results)){
            return '';
        }else{
            return response()->json($results);
        }
    }

    public function generateExcel(Request $request)
    {
        $user = Auth::user();
        $period = $request->period_id;
        $result = $this->posted->getPostedSummary($user,$period);
        
        $this->excel->setValues($result);
        return Excel::download($this->excel,'BankTransmittal.xlsx');
        //return view('app.payroll-transaction.bank-transmittal.transmittal',['data' => $result]);
        
    }
}
