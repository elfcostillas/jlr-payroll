<?php

namespace App\Http\Controllers\Deductions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Deductions\FixedDeductionMapper;
use App\Mappers\TimeKeepingMapper\PayrollPeriodMapper;
use Illuminate\Support\Facades\Auth;

class FixedDeductionController extends Controller
{
    //
    private $mapper;
    private $payperiod;


    public function __construct(FixedDeductionMapper $mapper,PayrollPeriodMapper $payperiod)
    {
        $this->mapper = $mapper;
        $this->payperiod = $payperiod;
    }

    public function index()
    {
        return view('app.deductions.fixed-deductions.index');
    }

    public function list(Request $request)
    {
        $type=$request->id;
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->mapper->list($type,$filter);

        return response()->json($result);
    }

    public function create(Request $request)
    {
        $request->request->add([
            'encoded_by' => Auth::user()->id,
            'encoded_on' => now()
        ]);
        $result = $this->mapper->insertValid($request->all());
        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function update(Request $request)
    {
       
        $result = $this->mapper->updateValid($request->only(['id','amount','is_stopped']));
        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function getEmployees(Request $request)
    {
        $result = $this->mapper->searchEmployee($request->filter);

        return response()->json($result);
    }

    public function getPayrollPeriod(Request $request)
    {
        $result = $this->payperiod->getPayrollPeriod();
        return response()->json($result);

    }

    public function getTypes(Request $request)
    {
        $result = $this->mapper->getTypes();
        return response()->json($result);

    }

}
