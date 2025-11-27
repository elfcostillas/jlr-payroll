<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use App\Mappers\PayrollTransaction\ThirteenthMonthMapper;
use Illuminate\Http\Request;

class ThirteenthMonthJLRController extends Controller
{
    //
    private $mapper;

    public function __construct(ThirteenthMonthMapper $mapper)
    {
        $this->mapper = $mapper;
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
}
