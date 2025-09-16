<?php

namespace App\Http\Controllers\EmployeeFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use App\Mappers\EmployeeFileMapper\EmployeeWeeklyMapper;
use App\Mappers\Admin\ActivityLogMapper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mappers\EmployeeFileMapper\OnlineRequestUserMapper;
use App\Mappers\EmployeeFileMapper\RatesMapper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    //
    private $mapper;
    private $log;
    private $weekly;
    private $online;
    private $rmapper;

    public function __construct(EmployeeMapper $mapper,ActivityLogMapper $log,EmployeeWeeklyMapper $weekly,OnlineRequestUserMapper $online,RatesMapper $rmapper)
    {
        $this->mapper = $mapper;
        $this->log = $log;
        $this->weekly = $weekly;
        $this->online = $online;
        $this->rmapper = $rmapper;
    }

    public function index()
    {
        $emp_stat = $this->mapper->getEmploymentStat();
        $exit_stat = $this->mapper->getExitStat();
        $pay_type = $this->mapper->getPayTypes();
        $level_desc = $this->mapper->getLevels();
        $userDept = $this->mapper->getUserDept(Auth::user()->biometric_id);
        $userLevel = $this->mapper->getUserlevel(Auth::user()->biometric_id);

        if($userDept==null)
        {
            return response()->json('Error : Biometric ID was not set. Please set your Biometric ID on Accounts >> Biometric ID to continue.');
        }
        //dd($userDept->dept_id);

      
        if($userDept->dept_id==8 || $userLevel->emp_level <= 2){
            $canSeeRates = true;
        }else{
            $canSeeRates = false;
        }
        
        return view('app.employee-file.employee-master-data.index',['emp_stat'=>$emp_stat, 'exit_stat'=>$exit_stat, 'pay_type'=>$pay_type, 'level_desc'=>$level_desc,'canSeeRates'=>$canSeeRates]);
    }

    public function list(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
            'search' => $request->input('search'),
        ];

        $result = $this->mapper->list($filter,'non-confi');

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

        if($data_arr['manual_wtax'] == ''){
            $data_arr['manual_wtax'] = null;
        }

        if($data_arr['id']==null){

            if($data_arr['biometric_id']==''){
                $biometric_id = $this->mapper->biometricIDGenerator();
                $data_arr['biometric_id']=$biometric_id;
            }

            $result = $this->mapper->insertValid($data_arr);
            $result2 = $this->weekly->insertValid($data_arr);

            if(is_object($result)){
                return response()->json($result)->setStatusCode(500, 'Error');
            }

            if($result){
                $record = $this->mapper->header($result);
                $this->createLog('create',$result,$record);
            }
            // $this->mapper->createOn100($data_arr);
        }else{
            $record = $this->mapper->header($data_arr['id']);
            
            $result = $this->mapper->updateValid($data_arr);
            $result2 = $this->weekly->insertValid($data_arr);
            $result3 = $this->weekly->updateValid($data_arr);
            if($result){
                //$record = $this->mapper->header($data_arr['id']);
                $this->createLog('update',$data_arr['id'],$record);
            }
            // $this->mapper->createOn100($data_arr);
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

    public function bioAssignment()
    {
        $data = $this->mapper->generateBiometricAssignment();
        //$data = $this->mapper->biometricIDGenerator();

        $emp_array = [];
        foreach($data['empname'] as $key => $value)
        {
            //dd($value->biometric_id,$value->empname);
            $emp_array[$value->biometric_id] = $value->empname;
        }

        return view('app.employee-file.employee-master-data.bio-assignment',['data' => $data,'emp' => $emp_array]);

    }

    public function copyToOR(Request $request)
    {
    
        $result = $this->mapper->header($request->id);
        $key = [
            'email' => trim($result->biometric_id)
        ];

        if($result){
            $msg = '';

            $msg .= ($result->emp_level) ? '' : 'Employee level not set.';
            $msg .= ($result->email) ? '' : 'Employee email not set.';
            $msg .= ($result->birthdate) ? '' : 'Employee birthdate not set.';
            $msg .= ($result->biometric_id) ? '' : 'Employee biometric id not set.';

            
           
            if($msg == ''){
                $bday = Carbon::createFromFormat("Y-m-d",$result->birthdate);

                // return response()->json((object) array('error'=>$bday->format('mdY')))->setStatusCode(500);
                 
                $data = [
                    'user_group_id' => trim($result->emp_level),
                    'biometric_id' => trim($result->biometric_id),
                    'password' => Hash::make(trim($bday->format('mdY'))),
                    'email' => trim($result->email),
                    'name' => trim($result->lastname).', '.trim($result->firstname) 
                ];
            
                $result = $this->online->updateOrCreate($data);
            }else{
                return response()->json((object) array('error'=>$msg))->setStatusCode(500); 
            }

        }else{
            $result = (object) array('error'=>"Record not found.");
        }
       return response()->json($result);

    }

    public function sendToEportal()
    {

        $employees = DB::connection('mysql')->table('employees')
        ->where('exit_status','=',1)
        ->whereNotNull('birthdate')
        ->whereNotNull('email')
        ->select('birthdate','emp_level','email','lastname','firstname','biometric_id')
        ->distinct()
        ->get();

        foreach($employees as $employee)
        {
            $bday = Carbon::createFromFormat("Y-m-d",$employee->birthdate);
            $data = [
                'user_group_id' => trim($employee->emp_level),
                'biometric_id' => trim($employee->biometric_id),
                'password' => Hash::make(trim($bday->format('mdY'))),
                'email' => trim($employee->email),
                'name' => trim($employee->lastname).', '.trim($employee->firstname) 
            ];
        
            $result = $this->online->updateOrCreate($data);
        }
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