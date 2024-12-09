<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Excel\ThirteenthMonthSG;
use App\Http\Controllers\Controller;
use App\Mappers\PayrollTransaction\ThirteenthMonthMapper;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ThirteenthMonthController extends Controller
{
    //
    private $mapper;
    private $excel;

    public function __construct(ThirteenthMonthMapper $mapper,ThirteenthMonthSG $excel)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
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

        // dd($request->year);
    }
}
