<?php

namespace App\Http\Controllers\Compentsations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Compensation\OtherIncomeWeeklyAppHeaderMapper;

class OtherIncomeWeeklyAppController extends Controller
{
    //
    private $mapper;

    public function __construct(OtherIncomeWeeklyAppHeaderMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.compensations.other-income-app-weekly.index');
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

    public function employeeList(Request $request)
    {
        $result = $this->mapper->empList($request->period_id);

        return response()->json($result);
    }
}
