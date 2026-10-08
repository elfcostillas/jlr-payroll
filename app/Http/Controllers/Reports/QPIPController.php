<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Mappers\Reports\QPIPMapper;
use Illuminate\Http\Request;

class QPIPController extends Controller
{
    //
    public $qpipMapper;

    public function __construct(QPIPMapper $qpipMapper)
    {
        // $this->middleware('auth');
        $this->qpipMapper = $qpipMapper;
    }

    public function index()
    {
        $quarters = [
            1 => 'January - March',
            2 => 'April - June',
            3 => 'July - September',
            4 => 'October - December'
        ];

        $quarterOptions = collect($quarters)
            ->map(fn ($text, $value) => [
                'value' => $value,
                'text' => $text,
            ])
            ->values();
       
        return view('app.reports.qpip.index', [
            'years' => $this->qpipMapper->getYears(),
            'quarters' => $quarterOptions
        ]);
    }

    public function sgWeb(Request $request)
    {
       
        $quarter = $request->input('quarter');
        $year = $request->input('year');
        $months = $this->qpipMapper->getMonths($quarter, $year);

        $data = $this->qpipMapper->buildSGData($quarter, $year);

        return view('app.reports.qpip.sg-web', [
            'data' => $data,
            'months' => $months,
            'quarter' => $quarter,
            'year' => $year
        ]);
    }

    public function ranknfileWeb(Request $request)
    {
        $quarter = $request->input('quarter');
        $year = $request->input('year');
        $months = $this->qpipMapper->getMonths($quarter, $year);

        $data = $this->qpipMapper->buildRankNFileData($quarter, $year); //buildDataForJLREmployee

        return view('app.reports.qpip.ranknfile', [
            'data' => $data,
            'months' => $months,
            'quarter' => $quarter,
            'year' => $year
        ]);
    }

    public function confiWeb(Request $request)
    {
        $quarter = $request->input('quarter');
        $year = $request->input('year');
        $months = $this->qpipMapper->getMonths($quarter, $year);

        $data = $this->qpipMapper->buildManagersAndSupervisorData($quarter, $year); //buildDataForJLREmployee

        return view('app.reports.qpip.confi', [
            'data' => $data,
            'months' => $months,
            'quarter' => $quarter,
            'year' => $year
        ]);
    }

    


}
