<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Mappers\TimeKeepingMapper\DailyTimeRecordMapper;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
    //
    private $dtr_mapper;

    public function __construct(DailyTimeRecordMapper $dtr_mapper) {
        $this->dtr_mapper  = $dtr_mapper;
    }

    public function index()
    {
        return view('app.reports.attendance.index');
    }

    public function generate($from,$to)
    {
        $result = $this->dtr_mapper->attendance_report($from,$to);

        return view('app.reports.attendance.web',['data' => $result]);
    }

    public function setAWOL($year)
    {
        $result = $this->dtr_mapper->awol_setter($year);
    }
}
