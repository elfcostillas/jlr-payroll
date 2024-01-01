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

       
}