<?php

namespace App\Http\Controllers\EmployeeFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use App\Mappers\Admin\ActivityLogMapper;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    //
    private $mapper;
    private $log;

    public function __construct(EmployeeMapper $mapper,ActivityLogMapper $log)
    {
        $this->mapper = $mapper;
        $this->log = $log;
    }

    public function index()
    {
        $emp_stat = $this->mapper->getEmploymentStat();
        $exit_stat = $this->mapper->getExitStat();
        $pay_type = $this->mapper->getPayTypes();
        $level_desc = $this->mapper->getLevels();
       
        return view('app.employee-file.employee-master-data.index',['emp_stat'=>$emp_stat, 'exit_stat'=>$exit_stat, 'pay_type'=>$pay_type, 'level_desc'=>$level_desc]);
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

    // public function create(Request $request)
    // {
    //     $result = $this->mapper->insertValid($request->all());

    //     if(is_object($result)){
	// 		return response()->json($result)->setStatusCode(500, 'Error');
	// 	}

    //     return response()->json($result);
    // }

    // public function update(Request $request)
    // {
    //     $result = $this->mapper->updateValid($request->all());

    //     if(is_object($result)){
	// 		return response()->json($result)->setStatusCode(500, 'Error');
	// 	}

    //     return response()->json($result);
    // }

    public function save(Request $request)
    {
        $data = json_decode($request->data);

        $data_arr = (array) $data;

        if($data_arr['pay_type']==1){
            $data_arr['is_daily'] = 'N';       
        }else{
            $data_arr['is_daily'] = 'Y';           
        }

        if($data_arr['id']==null){
            $result = $this->mapper->insertValid($data_arr);

            if(is_object($result)){
                return response()->json($result)->setStatusCode(500, 'Error');
            }

            if($result){
                $record = $this->mapper->header($result);
                $this->createLog('create',$result,$record);
            }
           
        }else{
            $record = $this->mapper->header($data_arr['id']);
            
            $result = $this->mapper->updateValid($data_arr);
            if($result){
                //$record = $this->mapper->header($data_arr['id']);
                $this->createLog('update',$data_arr['id'],$record);
            }
        }

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }


    public function readById(Request $request)
    {
        $result = $this->mapper->header($request->id);
        $this->createLog('view',$request->id,$result);
        return response()->json($result);
    }

    public function getJobTitles(Request $request)
    {
        $result = $this->mapper->getJobTitles($request->id);
        return response()->json($result);
    }

    private function createLog($action,$record_id,$record)
    {
        switch($action){
           
            case 'update' :
                    $before = $record;
                   
                    $after = $this->mapper->header($record_id);
                    $log_string = $this->toStringChanges($before,$after);
                   
                    
                break;
                
            case 'view' :  case 'create' :
                $log_string = $this->toString($record);
                break;

        }

        $log_data = [
            'log_timestamp' => now(),
            'log_module'=> 'employee_master',
            'log_user' => Auth::user()->id,
            'log_action' => $action,
            'log_data' => $log_string,
            'record_id' => $record_id
        ];

        if($log_string!=""){
            $result = $this->log->insertValid($log_data);
        }
        
       

    }

    private function toString($object)
    {
      
        $string = "";
        foreach($object->getAttributes() as $key => $value){
            $string .= "'".$key."'".'=>'."\"".$value."\", ";
        }
      
        return $string;
    }

    private function toStringChanges($before,$after)
    {
      
        $string = "";
        //dd($before,$after);
        foreach($before->getAttributes() as $key => $value){
            //dd($before,$after);
            //$string .="'".$key."'".' : "'. $before->$key.'"=>"'.$after->$key.'"';
            if($before->$key != $after->$key){
                //dd($before->$key,$after->$key);
               // dd($before->$key,$after->$key);
                //$string .= "'".$key."'".':'."\"".$before->$key."\" => "\"".$after->$key."\" , ";
                $string .="'".$key."'".' : "'. $before->$key.'"=>"'.$after->$key.'" ,';
            }
            
        }
      
        return $string;
    }



    // public function getEmploymentStat()
    // {
    //     $result = $this->mapper->getEmploymentStat();
    //     return response()->json($result);
    // }

    // public function getExitStat()
    // {
    //     $result = $this->mapper->getExitStat();
    //     return response()->json($result);
    // }

   

}


/*
$data = json_decode($request->data);
    $user = Auth::user();
    //dd(date_format(Carbon::createFromFormat('m/d/Y',$data->po_date),'Y-m-d'));
    $data->po_date = date_format(Carbon::createFromFormat('m/d/Y',$data->po_date),'Y-m-d');
    $data = (array) $data;

    if(is_object($data['po_supplier'])){
        $data['po_supplier']=$data['po_supplier']->contact_id;
    }
    */