<?php

namespace App\Http\Controllers\Compentsations;

use App\Excel\FixedCompensation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mappers\Compensation\FixedCompensationDetailMapper;
use App\Mappers\Compensation\FixedCompensationHeaderMapper;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class FixCompensationController extends Controller
{
    //
    protected $header;
    protected $detail;    
    protected $excel;    

    public function __construct(FixedCompensation $excel,FixedCompensationHeaderMapper $header,FixedCompensationDetailMapper $detail)
    {
        $this->header = $header;
        $this->detail = $detail;
        $this->excel = $excel;
    }

    public function index()
    {
        return view('app.compensations.fixed-compensation.index');
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

       $this->detail->createDetails($result);
       
        return response()->json($result);
    }

    public function getFixeddComp()
    {
        $result = $this->header->getFixeddComp();

        return response()->json($result);
    }

    public function getPayrollPeriod()
    {
        $result = $this->header->getPayrollPeriod();

        return response()->json($result);
    }

    public function readHeader(Request $request)
    {
        $result = $this->header->find($request->id);
        return response()->json($result);
    }

    public function readDetail(Request $request)
    {
        $result = $this->detail->list($request->id);

        return response()->json($result);
    }

    public function download(Request $request)
    {
        $header_id = $request->id;
        $result = $this->detail->list($header_id);

        // // return view('app.deductions.one-time.export',['data' => $result,'header_id'=>$header_id]);

        $this->excel->setValues($result,$header_id);
        return Excel::download($this->excel,'FixedCompensation'.$header_id.'.csv');

    }

    public function upload(Request $request)
    {
        $file = $request->file('files');
        $header_id = $request->header_id;

        $row = [];

        $path = Storage::disk('local')->put('uploads',$file);
       
        $thisFile = Storage::get($path);
        $content = explode("\n",mb_convert_encoding($thisFile, 'UTF-8', 'UTF-8'));

        foreach($content as $line)
        {
			$data = str_getcsv($line,",");

            if($data[0] == $header_id){
                if(array_key_exists(3,$data)){

                    
                    //if($data[3] != 0 && $data[3] != '')
                    //{
                        array_push($row,[
                            'header_id' => $header_id,
                            'biometric_id' => $data[1],
                            'total_amount' => $data[3]
                        ]);
                    //}
                }else {
                    dd($data);
                }
                
            }

        }

        $result = $this->detail->uploadCSV($row,$header_id);

        return response()->json($result);
    }

    public function updateDetail(Request $request)
    {
        // $result = $this->detail->updateValid($request->all());

        // return response()->json($result);

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
