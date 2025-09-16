<?php

namespace App\Http\Controllers\Reports;

use App\Excel\PayrollSupportGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\PayrollPeriodWeeklyMapper;
use App\Mappers\PayrollTransaction\PostedPayrollRegisterWeeklyMapper;
use Maatwebsite\Excel\Facades\Excel;

class PayrollSupportGroupController extends Controller
{
    //
    
    private $period_mapper;
    private $posted;
    private $excel;

    public function __construct(PayrollPeriodWeeklyMapper $period_mapper,PostedPayrollRegisterWeeklyMapper $posted,PayrollSupportGroup $excel)
    {
        $this->period_mapper = $period_mapper;
        $this->posted = $posted;
        $this->excel = $excel;
    }

    public function index()
    {
        return view('app.reports.payroll-support-group.index');
    }

    public function periodList(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->period_mapper->list($filter);

        return response()->json($result);
    }

    public function downloadPayrollReport(Request $request)
    {
        // dd($request->id);
        $period = $this->period_mapper->find($request->id);
        $result = $this->posted->grossDeductionNetpay($request->id);

        $this->excel->setValues($result,$period);
        return Excel::download($this->excel,'PayrollSupportGroup.xlsx');

        // return view('app.reports.payroll-support-group.export',['data' => $result,'label' => $period]);

    }
}
