<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Mappers\TimeKeepingMapper\LeavesAbsenceMapper;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonPeriod;

use App\Mappers\Accounts\LeaveRequestHeaderMapper;
use App\Mappers\Accounts\LeaveRequestDetailMapper;

class LeavesAbsencesController extends Controller
{
    //
    private $mapper;
    private $header;
    private $detail;

    public function __construct(LeavesAbsenceMapper $mapper,LeaveRequestHeaderMapper $header,LeaveRequestDetailMapper $detail)
    {
        $this->mapper = $mapper;
        $this->header = $header;
        $this->detail = $detail;
    }

    public function index()
    {
        return view('app.timekeeping.leave-absence.index');
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

    public function receive(Request $request)
    {
        $user = Auth::user();
        $leave = $this->mapper->header($request->id);

        if($leave!=null){
            $leave->received_by= $user->id;
            $leave->received_time= now();
        }

        $result = $this->mapper->updateValid($leave->toArray());

        return response()->json($result);

    }

	public function getLeavesFrom100(){
        $result = $this->mapper->getLeavesFrom100();

        foreach($result as $leave)
        {

            switch($leave->leave_type){
                case 'vacation': $type = 'VL'; break;
                case 'sick': $type = 'SL'; break;
                case 'others': $type = 'O'; break;
                case 'emergency': $type = 'EL'; break;
                case 'undertime': $type = 'UT'; break;
                case 'maternity/pater': $type = 'MP'; break;
                case 'bl': $type = 'BL'; break;
                case 'svl': $type = 'VL'; break;
                
            }

            $data = [
                'biometric_id' => $leave->biometrics_id,
                'encoded_on' => now(),
                'encoded_by' => Auth::user()->id,
                'request_date' => now(),
                'leave_type' => $type,
                'date_from' => $leave->inclusive_from,
                'date_to' => $leave->inclusive_to,
                'remarks' => $leave->reason,
                'received_by' => Auth::user()->id,
                'received_time' => now(),
                'document_status' => 'POSTED',
                'w_pay' => $leave->w_pay,
                'wo_pay' => $leave->wo_pay,
                'time_from' => $leave->time_from,
                'time_to' => $leave->time_to,
                'hours' => $leave->hours
            ];

            $result = $this->header->insertValid($data);

            if(!is_object($result)){
                $this->detail->createDates2($data,$result);
            }

           
        }
    }
}

/*

vacation
sick
others
emergency
undertime
maternity/pater
bl
svl


  +"biometrics_id": "149"
  +"o200_id": "32348"
  +"emp_id": "149"
  +"date_filed": "2022-08-30"
  +"inclusive_from": "2022-09-01"
  +"inclusive_to": "2022-09-01"
  +"reason": "bday leave "
  +"time_from": "07:00"
  +"time_to": "16:00"
  +"leave_type": "bl"
  +"w_pay": "0.00"
  +"wo_pay": "1.00"*/
