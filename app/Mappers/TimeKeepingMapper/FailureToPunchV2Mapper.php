<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FailureToPunchV2Mapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\FailureToPunchV2';
    protected $rules = [
        'biometric_id' => 'required|sometimes',
        'ftp_date' => 'required|sometimes',
        'ftp_reason' => 'required|sometimes',
        'ftp_type' => 'required|sometimes',
    ];
   
    public function find($id)
    {
        $result = $this->model->find($id);

        return $result;
    }

    public function list($filter)
    {
        $result = $this->model->select(DB::raw("
        case 
            when ftp_type = 'OB' Then 'Official Business'
            when ftp_type = 'PR' Then 'Personal Reason'
            else ftp_type
        end as ftp_remarks
        ,ftp_hr.*,employee_names_vw.employee_name"))
            ->from('ftp_hr')
            ->join('employee_names_vw','ftp_hr.biometric_id','=','employee_names_vw.biometric_id');

        $total = $result->count();

        $result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('id','DESC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

    }

    public function getEmployees()
    {
        //select * from employee_names_vw where exit_status =1;
        $result = $this->model->select()->from('employee_names_vw')->where('exit_status','=',1);

        return $result->get();
    }

    public function post($arr)
    {
        $blanks = [];

        DB::connection('mysql')->table('edtr_raw')
            ->where('src','=','ftp')
            ->where('biometric_id',$arr['biometric_id'])
            ->where('punch_date','=',$arr['ftp_date'])
            ->delete();

        if($arr['time_in']){
            array_push($blanks,[
                'punch_date' => $arr['ftp_date'],
                'punch_time' => $arr['time_in'],
                'biometric_id' => $arr['biometric_id'],
                'cstate' => 'C/In',
                'src' => 'ftp',
                'src_id' => $arr['id']
            ]);
        }

        if($arr['time_out']){
            array_push($blanks,[
                'punch_date' => $arr['ftp_date'],
                'punch_time' => $arr['time_out'],
                'biometric_id' => $arr['biometric_id'],
                'cstate' => 'C/Out',
                'src' => 'ftp',
                'src_id' => $arr['id']
            ]);
        }

        if($arr['ot_in']){
            array_push($blanks,[
                'punch_date' => $arr['ftp_date'],
                'punch_time' => $arr['ot_in'],
                'biometric_id' => $arr['biometric_id'],
                'cstate' => 'OT/In',
                'src' => 'ftp',
                'src_id' => $arr['id']
            ]);
        }

        if($arr['ot_out']){
            array_push($blanks,[
                'punch_date' => $arr['ftp_date'],
                'punch_time' => $arr['ot_out'],
                'biometric_id' => $arr['biometric_id'],
                'cstate' => 'OT/Out',
                'src' => 'ftp',
                'src_id' => $arr['id']
            ]);
        }

        $result = DB::connection('mysql')->table('edtr_raw')->insertOrIgnore($blanks);

    }

       
}

/*
 "biometric_id" => "205"
  "ftp_date" => "2024-01-18"
  "ftp_type" => "PR"
  "ftp_reason" => "sdfsdfsd"
  "time_in" => "01:00"
  "time_out" => null
  "ot_in" => null
  "ot_out" => null
  "ftp_status" => "POSTED"
  "created_by" => "1"
  "created_on" => "2024-01-18 14:30:05"*/