<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Mappers\TimeKeepingMapper\LeavesAbsenceMapper;

class LeavesAbsencesController extends Controller
{
    //
    private $mapper;

    public function __construct(LeavesAbsenceMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.leave-absence.index');
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

        $result = $this->mapper->list($filter);

        return response()->json($result);
    }
}
