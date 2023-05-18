<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Accounts\LeaveRequestHeaderMapper;
use App\Mappers\Accounts\LeaveRequestDetailMapper;
use App\Mappers\TimeKeepingMapper\LeaveCreditsMapper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    //
    private $header;
    private $detail;
    private $credits;

    public function __construct(LeaveRequestHeaderMapper $header,LeaveRequestDetailMapper $detail,LeaveCreditsMapper $credits)
    {
        $this->header = $header;
        $this->detail = $detail;
        $this->credits = $credits;
    }

    public function index()
    {
        return view('app.accounts.leave-request.index');
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

        $result = $this->header->list($filter);

        return response()->json($result);
    }

    public function save(Request $request)
    {
        $data = json_decode($request->data);

        $data_arr = (array) $data;


        if($data_arr['id']==null){
            $data_arr['encoded_on']= now();
            $data_arr['request_date']= now();
            $data_arr['encoded_by']= Auth::user()->id;

            $result = $this->header->insertValid($data_arr);

            if(is_object($result)){
                //return response()->json($result->messages);
                //dd($result->error['messages']);
                if($result->error['messages']=='Duplicate.'){
                    $user = $this->header->getEncoder($data_arr['biometric_id'],$data_arr['date_from'],$data_arr['date_to']);
                   
                    return response()->json(['error' =>  $user['name'] .' has already filed a leave on the specefied date(s). with request id '.$user['id'].'.'])->setStatusCode(500, 'Error');
                }
                return response()->json($result->error['messages'])->setStatusCode(500, 'Error');
            }

            $this->detail->createDates($data_arr,$result);
            
        }else{
            $result = $this->header->updateValid($data_arr);
            
            if(is_object($result)){
                return response()->json($result)->setStatusCode(500, 'Error');
            }
        }

        return response()->json($result);
    }

    public function readHeader(Request $request)
    {
        $result = $this->header->header($request->id);
        return response()->json($result);
    }       

    public function readDetails(Request $request)
    {
        $result = $this->detail->listDates($request->id);

        return response()->json($result);

    }

    public function updateDetails(Request $request)
    {

    }

    public function getEmployees(Request $request)
    {
        $result = $this->header->searchEmployee($request->filter);

        return response()->json($result);
    }

    public function updateDetail(Request $request)
    {
        if($request->with_pay+$request->without_pay>9)
        {
            return response()->json(['error'=>'Invalid no of days.'])->setStatusCode(500, 'Error');
        }
        $result = $this->detail->updateValid($request->all());

        return response()->json($result);
    }

    public function recreate(Request $request)
    {
        $result = $this->header->header($request->id);
        
        $this->detail->deleteDetail($request->id);
        $dates = ['date_from'=>$result->date_from,'date_to' => $result->date_to];

        //dd($dates);
        $this->detail->createDates($dates,$result->id);

        return response()->json($result);
    }

    public function showBalance(Request $request)
    {
        $year = Carbon::createFromFormat('Y-m-d',$request->from)->format('Y');
        $biometric_id = $request->biometric_id;
       
        $start = $year.'-01-01';
        $end = $year.'-12-31';

        $data = $this->credits->showLeaves($biometric_id,$start,$end);

        $leave_credits = $this->credits->getLeaveCredits($biometric_id,$year);

        return view('app.accounts.leave-request.print',['data' => $data,'leave_credits'=>$leave_credits]);
    }
}


/*
SELECT * FROM leave_request_header INNER JOIN employees ON leave_request_header.biometric_id = employees.biometric_id
LEFT JOIN divisions ON employees.division_id = divisions.id
LEFT JOIN departments ON employees.dept_id = departments.id
LEFT JOIN job_titles ON employees.job_title_id = job_titles.id;


SELECT * FROM leave_request_header 
INNER JOIN employees ON leave_request_header.biometric_id = employees.biometric_id
LEFT JOIN divisions ON leave_request_header.division_id = divisions.id
LEFT JOIN departments ON leave_request_header.dept_id = departments.id
LEFT JOIN job_titles ON leave_request_header.job_title_id = job_titles.id
LEFT JOIN leave_request_type ON leave_type_code = leave_type
LEFT JOIN employees AS approver ON approver.biometric_id = acknowledge_by
LEFT JOIN employees AS hr_staff ON hr_staff.biometric_id = received_by
ORDER BY leave_request_header.id

*/

