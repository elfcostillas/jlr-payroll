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
        return view('app.reports.qpip.sg-web');
    }
}
