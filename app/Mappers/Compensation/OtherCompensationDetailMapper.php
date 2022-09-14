<?php

namespace App\Mappers\Compensation;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OtherCompensationDetailMapper extends AbstractMapper {
    protected $modelClassName = 'App\Models\Compensation\OtherCompensationDetail';
    protected $rules = [
    	'header_id' => 'required|sometimes',
        'biometric_id' => 'required|sometimes',
        'total_amount' => 'required|sometimes',
    ];

    public function list($id,$filter)
    {
        // $result = $this->model->select(DB::raw("employee_names_vw.*,compensation_fixed_details.line_id,IFNULL(compensation_fixed_details.total_amount,0.00) AS total_amount,header_id"))
        // ->from('employee_names_vw')
        // ->join('employees','employees.biometric_id','=','employee_names_vw.biometric_id')
        // ->leftJoin('compensation_fixed_details','employee_names_vw.biometric_id','=','compensation_fixed_details.biometric_id')
        // ->where('header_id',$id);
        // ->orWhere(function($query) { 
        //     $query->whereNull('deduction_type');
        //     $query->where('employee_names_vw.exit_status',1);
        // });

        /*
        SELECT employee_names_vw.biometric_id,employee_names_vw.employee_name,line_id,header_id,total_amount FROM employee_names_vw 
        INNER JOIN employees ON employees.biometric_id = employee_names_vw.biometric_id
        LEFT JOIN compensation_other_details ON compensation_other_details.biometric_id = employee_names_vw.biometric_id
        */

        $result = $this->model->select(DB::raw("employee_names_vw.biometric_id,employee_names_vw.employee_name,line_id,header_id,ifnull(total_amount,0.00) total_amount"))
                              ->from("employee_names_vw")
                              ->join('employees','employees.biometric_id','=','employee_names_vw.biometric_id')
                              ->leftJoin('compensation_other_details','employee_names_vw.biometric_id','=','compensation_other_details.biometric_id')
                              ->where('header_id',$id)
                              ->orWhere(function($query) { 
                                    $query->whereNull('header_id');
                                    $query->where('employee_names_vw.exit_status',1);
                                });


        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('lastname','asc')->orderBy('firstname','asc');

		return [
			'total' => $total,
			'data' => $result->get()
		];
    }

}