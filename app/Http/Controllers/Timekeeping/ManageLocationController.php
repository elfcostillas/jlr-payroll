<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\ManageLocationMapper;

class ManageLocationController extends Controller
{
    //
    private $mapper;

    function __construct(ManageLocationMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.manage-location.index');
    }

    public function list()
    {
        $result = $this->mapper->listPeriod();

        return response()->json($result);
    }

    public function employeeList(Request $request)
    {

        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
            'search' => $request->input('search'),
        ];

        // $result = $this->mapper->list($filter);
        // dd($request->period_id);
        $result = $this->mapper->employeeList($request->period_id,$filter);

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $arr = [
            'id' => $request->id ,
            'biometric_id' => $request->biometric_id ,
            'period_id' => $request->period_id ,
            'loc_id' => $request->loc_id,
            
        ];

        $result = $this->mapper->updateValid($arr);

        return response()->json($result);
    }
}
