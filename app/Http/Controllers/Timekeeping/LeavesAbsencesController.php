<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Mappers\TimeKeepingMapper\LeavesAbsenceMapper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Mappers\Accounts\LeaveRequestHeaderMapper;
use App\Mappers\Accounts\LeaveRequestDetailMapper;
use App\Mappers\TimeKeepingMapper\LeaveCreditsMapper;

class LeavesAbsencesController extends Controller
{
    //
    private $mapper;
    private $header;
    private $detail;
    private $credits;

    public function __construct(LeavesAbsenceMapper $mapper,LeaveRequestHeaderMapper $header,LeaveRequestDetailMapper $detail,LeaveCreditsMapper $credits)
    {
        $this->mapper = $mapper;
        $this->header = $header;
        $this->detail = $detail;
        $this->credits = $credits;
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
        $header = $this->mapper->header($request->id);
      
        $leave_date = Carbon::createFromFormat('Y-m-d',$header->date_from);

        $year = $leave_date->format('Y');
        $start = $year.'-01-01';
        $end = $year.'-12-31';

        $consumed = $this->credits->getBalance( $year,$start,$end,$header->biometric_id);

        if($consumed){
            $balance_vl = ($consumed[0]->vacation_leave - $consumed[0]->VL_PAY) * 8;  
            $balance_sl = ($consumed[0]->sick_leave - $consumed[0]->SL_PAY) * 8;  
        }else {
            $balance_vl = 0;
            $balance_sl = 0;
        }

        $details = $this->detail->listDates($request->id);

        foreach($details as $leave)
        {
            if($header->leave_type=='SL' || $header->leave_type=='VL' || $header->leave_type=='E')
            {

                $w_pay = $leave->with_pay;
                $wo_pay = $leave->without_pay;

                if($header->leave_type=='SL'){
                    if(($balance_sl-$w_pay)>=0 && ($w_pay>0)){
                        $ye_pay = ($balance_sl - $w_pay >=0) ? $w_pay : $w_pay - $balance_sl;
                        $no_pay = ($balance_sl - $w_pay >=0) ? $wo_pay : ($balance_sl - $w_pay) + $wo_pay;
                        $balance_sl = ($balance_sl- $ye_pay <= 0) ? 0 : $balance_sl- $ye_pay;
                    }else{
                        $ye_pay = 0;
                        $no_pay = $w_pay + $wo_pay;
                    }
                } else {
                    if(($balance_vl-$w_pay)>=0 || ($w_pay==0)){
                        $ye_pay = ($balance_vl - $w_pay >=0) ? $w_pay : $w_pay - $balance_vl;
                        $no_pay = ($balance_vl - $w_pay >=0) ? $wo_pay : ($balance_vl - $w_pay) + $wo_pay;
                        $balance_vl = ($balance_vl- $ye_pay <= 0) ? 0 : $balance_vl- $ye_pay;
                    }else{
                        $ye_pay = 0;
                        $no_pay = $w_pay + $wo_pay;
                    }
                }

                $leave->with_pay = $ye_pay;
                $leave->without_pay = $no_pay;

                $resultd = $this->detail->updateValid($leave->toArray());
            }
        }

        if($header!=null){
            $header->received_by= $user->id;
            $header->received_time= now();
        }

        $result = $this->mapper->updateValid($header->toArray());

        return response()->json($result);

        // $leave_date = Carbon::createFromFormat('Y-m-d',$request->leave_date);

        // $year = $leave->format('Y');
        // $start = $year.'-01-01';
        // $end = $year.'-12-31';

        // $consumed = $this->credits->getBalance( $year,$start,$end,$header->biometric_id);

        // $balance_vl = ($consumed[0]->vacation_leave - $consumed[0]->VL_PAY) * 8;  
        // $balance_sl = ($consumed[0]->sick_leave - $consumed[0]->SL_PAY) * 8;   

        // $w_pay = ($request->with_pay) ? $request->with_pay : 0;
        // $wo_pay = ($request->without_pay)? $request->without_pay : 0;

        // dd($consumed);

        // if($leave!=null){
        //     $leave->received_by= $user->id;
        //     $leave->received_time= now();
        // }

        //$result = $this->mapper->updateValid($leave->toArray());

        //return response()->json($result);

    }

    public function unpost(Request $request)
    {
        $user = Auth::user();
        $leave = $this->mapper->header($request->id);

        if($leave!=null){
            $leave->received_by= null;
            $leave->received_time= null;
            $leave->document_status = 'DRAFT';
        }

        $result = $this->mapper->updateValid($leave->toArray());

        return response()->json($result);

    }

    public function updateDetail(Request $request)
    {
        if($request->with_pay+$request->without_pay>9)
        {
            return response()->json(['error'=>'Invalid no of days.'])->setStatusCode(500, 'Error');
        }

        if($request->is_canceled=='Y'){
            // dd(trim($request->leave_remarks));
            if(trim($request->leave_remarks) ==''){
                return response()->json(['error' => 'Please provide reason.'])->setStatusCode(500, 'Error');
              
            }
        }

        $result = $this->detail->updateValid($request->all());

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

    public function makeQueryfor100(Request $request)
    {
        $credits = $this->mapper->getEncodedLeaveCredits(2023);

        foreach($credits as $emp)
        {
            $old_id = $this->mapper->getOldId($emp->biometric_id);
            $bal = $emp->vacation_leave + $emp->sick_leave;

            //echo var_dump($old_id).' - '.$emp->biometric_id.'-'.$emp->employee_name."<hr>";
            $qry = "update hr_leavecredits set credits = ".$bal." Where year=2023 and o201_id=".$old_id->o1_id.";<br>";
            //$qry = "insert into hr_leavecredits (o201_id,credits,year,balance) values (".$old_id->o1_id.",".$bal.",2023,".$bal.");<br>";

            echo $qry;
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
