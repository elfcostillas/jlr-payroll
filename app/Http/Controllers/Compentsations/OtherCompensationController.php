<?php

namespace App\Http\Controllers\Compentsations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Mappers\Compensation\OtherCompensationHeaderMapper;
use App\Mappers\Compensation\OtherCompensationDetailMapper;

class OtherCompensationController extends Controller
{
    //
    protected $header;
    protected $detail;
    public function __construct(OtherCompensationHeaderMapper $header,OtherCompensationDetailMapper $detail)
    {
        $this->header = $header;
        $this->detail = $detail;
    }

    public function index()
    {
        return view('app.compensations.other-compensation.index');
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
        $result = $this->header->find($request->id);
        return response()->json($result);
    }

    public function getOtherComp()
    {
        $result = $this->header->getOtherComp();
        return response()->json($result);
    }

    public function getPayrollPeriod()
    {
        $result = $this->header->getPayrollPeriod();

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

    public function readDetail(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->detail->list($request->id,$filter);

        return response()->json($result);
    }

    public function updateDetail(Request $request)
    {
        $data = $request->models;

        foreach($data as $row)
        {
            if($row['line_id']==null || $row['line_id']=='' ){
                $result = $this->detail->insertValid($row);
            }else{
                $result = $this->detail->updateValid($row);
            }

            if(is_object($result)){
                return response()->json($result)->setStatusCode(500, 'Error');
            }
        }

        return response()->json($result);
    }
}
