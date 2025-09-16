<?php

namespace App\Http\Controllers\Deductions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Deductions\OneTimeDeductionHeaderMapper;
use App\Mappers\Deductions\OneTimeDeductionDetailMapper;
use Illuminate\Support\Facades\Auth;
use App\Mappers\TimeKeepingMapper\PayrollPeriodMapper;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\OneTimeDeduction;
use Illuminate\Support\Facades\Storage;

class OneTimeDeductionController extends Controller
{
    //
    private $header;
    private $detail;   
    private $payperiod;
    private $excel;
 

    public function __construct(OneTimeDeductionHeaderMapper $header,OneTimeDeductionDetailMapper $detail,PayrollPeriodMapper $payperiod,OneTimeDeduction $excel)
    {
        $this->header = $header;
        $this->detail = $detail;        
        $this->payperiod = $payperiod;    
        $this->excel = $excel;
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
        // $result = $this->detail->updateValid($request->all());
        // if(is_object($result)){
		// 	return response()->json($result)->setStatusCode(500, 'Error');
		// }

        // return response()->json($result);

        $data = $request->models;
        foreach($data as $row)
        {
            if($row['line_id']==''||$row['line_id']==null){
                $result = $this->detail->insertValid($row);
            }else{
                $result = $this->detail->updateValid($row);
            }
           
            if(is_object($result)){
                return response()->json($result)->setStatusCode(500, 'Error');
            }
        } 
        return response()->json(true);
    }
    
    public function destroyDetail(Request $request) {
        $result = $this->detail->destroy($request->all());
        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function download(Request $request)
    {
        $header_id = $request->id;
        $result = $this->detail->list($header_id);

        // return view('app.deductions.one-time.export',['data' => $result,'header_id'=>$header_id]);

        $this->excel->setValues($result,$header_id);
        return Excel::download($this->excel,'OneTimeDeduction'.$header_id.'.xlsx');

    }

    public function upload(Request $request)
    {
        $header_id = $request->header_id;
        $file = $request->file('files');

        $logs = [];
        $dtr = [];

        $path = Storage::disk('local')->put('uploads',$file);
       
        $thisFile = Storage::get($path);
        $content = explode("\n",mb_convert_encoding($thisFile, 'UTF-8', 'UTF-8'));

        foreach($content as $line)
        {
			$data = str_getcsv($line,",");

            if($data[0] == $header_id){
                if(array_key_exists(3,$data)){

                    
                    if($data[3] != 0 && $data[3] != '')
                    {
                        array_push($logs,[
                            'header_id' => $header_id,
                            'biometric_id' => $data[1],
                            'amount' => $data[3]
                        ]);
                    }
                }else {
                    dd($data);
                }
            }

        }

       $result = $this->detail->uploadCSV($logs,$header_id);

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