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

    public function update(Request $request)
    {
        $data = $request->models;
        
        foreach($data as $line)
        {
            $result = $this->mapper->updateOrCreate([
                                                        'period_id'=>$line['period_id'],
                                                        'biometric_id'=>$line['biometric_id']
                                                    ],
                                                    [
                                                        "earnings" => $line['earnings'],
                                                        "deductions" => $line['deductions'],
                                                        "retro_pay" => $line['retro_pay'],
                                                        "canteen" => $line['canteen_bpn'] + $line['canteen_bps'] + $line['canteen_agg'],
                                                        "remarks" => $line['remarks'],
                                                        "canteen_bpn" => $line['canteen_bpn'],
                                                        "canteen_bps" => $line['canteen_bps'],
                                                        "canteen_agg" => $line['canteen_agg'],
                                                    ]);
        }

        return response()->json(true);
    }
}
