<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\DepartmentMapper;
use App\Mappers\SettingsMapper\DefaultScheduleMapper;

class DefaultScheduleController extends Controller
{
    //
    private $dept;
    private $sched;

    public function __construct(DepartmentMapper $dept,DefaultScheduleMapper $sched)
    {
        $this->dept = $dept;
        $this->sched = $sched;
    }

    public function index()
    {
        return view('app.settings.default-schedule.index');
    }

    public function list(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->sched->list($filter);

        return response()->json($result);
    }

    public function update(Request $request)
    {
        if($request->line_id){
            $data = [
                'line_id' => $request->line_id,
                'dept_id' => $request->dept_id,
                'schedule_id' => $request->schedule_id,
                'schedule_sat' => $request->schedule_sat
            ];
            $result = $this->sched->updateValid($data);
        }else{
            $data = [
                'dept_id' => $request->dept_id,
                'schedule_id' => $request->schedule_id,
                'schedule_sat' => $request->schedule_sat
            ];
            $result = $this->sched->insertValid($data);
        }
        // $data = [
        //     'dept_id' => $request->dept_id,
        //     'schedule_id' => $request->schedule_id,
        //     'schedule_sat' => $request->schedule_sat
        // ];

        //$result = $this->sched->updateOrCreate($data);
        
        return response()->json($result);
    }
}
