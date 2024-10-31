<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use App\Mappers\PayrollTransaction\ThirteenthMonthMapper;
use Illuminate\Http\Request;

class ThirteenthMonthController extends Controller
{
    //
    private $mapper;

    public function __construct(ThirteenthMonthMapper $mapper)
    {
        $this->mapper = $mapper;
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
}
