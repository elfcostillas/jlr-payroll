<?php

namespace App\Http\Controllers\Deductions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Deductions\OneTimeDeductionHeaderMapper;
use App\Mappers\Deductions\OneTimeDeductionDetailMapper;
use Illuminate\Support\Facades\Auth;
use App\Mappers\TimeKeepingMapper\PayrollPeriodMapper;

class OneTimeDeductionController extends Controller
{
    //
    private $header;
    private $detail;   
    private $payperiod;
 

    public function __construct(OneTimeDeductionHeaderMapper $header,OneTimeDeductionDetailMapper $detail,PayrollPeriodMapper $payperiod)
    {
        $this->header = $header;
        $this->detail = $detail;        
        $this->payperiod = $payperiod;    
    }

    public function index()
    {
        return view('app.deductions.one-time.index');
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

        $result = $this->header->list($type,$filter);

        return response()->json($result);
    }

    public function readHeader(Request $request)
    {
        $result = $this->header->header($request->id);
        return response()->json($result);
    }

    public function save(Request $request) {

        $data = json_decode($request->data);

        $data_arr = (array) $data;

        if($data_arr['id']==null){
            $data_arr['encoded_by'] = Auth::user()->id;
            $data_arr['encoded_on'] = now();
            $result = $this->header->insertValid($data_arr);
        }else{
            $result = $this->header->updateValid($data_arr);
        }

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

  

    public function getTypes(Request $request)
    {
        $result = $this->header->getTypes();
        return response()->json($result);

    }

    public function getPayrollPeriod(Request $request)
    {
        $result = $this->payperiod->getPayrollPeriod();
        return response()->json($result);

    }

    public function readDetail(Request $request)
    {
        $header_id = $request->id;
        $result = $this->detail->list($header_id);

        return response()->json($result);
    }

    public function getEmployees(Request $request)
    {
        $result = $this->header->searchEmployee($request->filter);

        return response()->json($result);
    }
    
    public function createDetail(Request $request) {
        $result = $this->detail->insertValid($request->all());
        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }
    
    public function updateDetail(Request $request) {
        $result = $this->detail->updateValid($request->all());
        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }
    
    public function destroyDetail(Request $request) {
        $result = $this->detail->destroy($request->all());
        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }
}


/*

  $result =  $this->mapper->insertValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
        
        */