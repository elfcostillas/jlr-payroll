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

    public function tardindessYearly(Request $request)
    {
        $month[1] = 'January';
        $month[2] = 'February';
        $month[3] = 'March';
        $month[4] = 'April';
        $month[5] = 'May';
        $month[6] = 'June';
        $month[7] = 'July';
        $month[8] = 'August';
        $month[9] = 'September';
        $month[10] = 'October';
        $month[11] = 'November';
        $month[12] = 'December';
        
        $emp = $this->mapper->getEmployees($request->year);

        $data = $this->mapper->buildData($month,$emp,$request->year);
        
        return view('app.reports.tardiness-reports.yearly',['data'=> $data,'month'=>$month,'emp' => $emp]); 
    }


}
