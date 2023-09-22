<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\PayrollPeriodMapper;


class PayrollPeriodController extends Controller
{
    //
    protected $mapper;

    public function __construct(PayrollPeriodMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        
        return view('app.timekeeping.payroll-period.index');
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

    public function create(Request $request)
    {
        $result = $this->mapper->insertValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        if($data['inProgress']=='Y')
        {
            $flag = $this->mapper->checkAtherForProgress($data);
            if($flag){
                return response()->json(['error' => 'Another payroll period inprogress detected.'])->setStatusCode(500, 'Error');
            }
        }

        $result = $this->mapper->updateValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }


}
