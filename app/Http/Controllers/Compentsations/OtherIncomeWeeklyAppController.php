<?php

namespace App\Http\Controllers\Compentsations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Compensation\OtherIncomeWeeklyAppHeaderMapper;

class OtherIncomeWeeklyAppController extends Controller
{
    //

    public function __construct()
    {

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
}
