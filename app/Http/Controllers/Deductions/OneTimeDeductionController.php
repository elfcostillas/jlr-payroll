<?php

namespace App\Http\Controllers\Deductions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Deductions\OneTimeDeductionHeaderMapper;
use App\Mappers\Deductions\OneTimeDeductionDetailMapper;

class OneTimeDeductionController extends Controller
{
    //
    private $header;
    private $detail;    

    public function __construct(OneTimeDeductionHeaderMapper $header,OneTimeDeductionDetailMapper $detail)
    {
        $this->header = $header;
        $this->detail = $detail;            
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

    public function getTypes(Request $request)
    {
        $result = $this->header->getTypes();
        return response()->json($result);

    }

    public function getPayrollPeriod(Request $request)
    {
        $result = $this->header->getPayrollPeriod();
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