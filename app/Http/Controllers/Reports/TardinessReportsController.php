<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Reports\TardinessReportMapper;

class TardinessReportsController extends Controller
{
    //
    private $mapper;

    public function __construct(TardinessReportMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.reports.tardiness-reports.index');
    }

    public function detailedReport(Request $request)
    {
       
        $filter = [
            'from' => $request->from,
            'to' => $request->to,
            'div_id' => $request->div,
            'dept_id' => $request->dept,
        ];

        $result = $this->mapper->detailed($filter);
        
        return view('app.reports.tardiness-reports.detailed',['data'=> $result]);
    
    }

    public function summarizedReport(Request $request)
    {
        $filter = [
            'from' => $request->from,
            'to' => $request->to,
            'div_id' => $request->div,
            'dept_id' => $request->dept,
        ];

        $result = $this->mapper->summary($filter);

        return view('app.reports.tardiness-reports.summary',['data'=> $result]);
    }
}
