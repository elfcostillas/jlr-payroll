<?php

namespace App\Http\Controllers\Deductions;

use App\Http\Controllers\Controller;
use App\Mappers\Deductions\GovtLoanSGMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GovtLoanSGController extends Controller
{
    //

    private $mapper;

    public function __construct(GovtLoanSGMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.deductions.govt-loans-sg.index');
    }

    public function list(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->mapper->list($biometric_id,$filter);

        return response()->json($result);
    }

    public function getEmployees(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->mapper->employeelist($filter);

        return response()->json($result);
    }

    public function getPayrollPeriod()
    {
        $result = $this->mapper->getPayrollPeriod();
        return response()->json($result);
    }
    
    public function getTypes()
    {
        $result = $this->mapper->getTypes();
        return response()->json($result);
    }

    public function  save(Request $request)
    {
        $data = json_decode($request->data);

        $data_arr = (array) $data;

        if($data_arr['id']==null || $data_arr['id']=='null'){
            $data_arr['encoded_by'] = Auth::user()->id;
            $data_arr['encoded_on'] = now();
           
            $result = $this->mapper->insertValid($data_arr);
        }else{
            $result = $this->mapper->updateValid($data_arr);
        }

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

        public function getDeductSched()
    {
        $result = $this->mapper->getDeductSched();

        return response()->json($result);
    }

    public function readHeader(Request $request)
    {
        $result = $this->mapper->header($request->id);

        return response()->json($result);
    }



}
