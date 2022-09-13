<?php

namespace App\Mappers\Compensation;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FixedCompensationDetailMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Compensation\FixCompensationDetail';
    protected $rules = [
    	
    ];

    public function list($id)
    {
        $result = $this->model->select(DB::raw("employee_names_vw.*,compensation_fixed_details.line_id,IFNULL(compensation_fixed_details.total_amount,0.00) AS total_amount,header_id"))
        ->from('employee_names_vw')
        ->join('employees','employees.biometric_id','=','employee_names_vw.biometric_id')
        ->leftJoin('compensation_fixed_details','employee_names_vw.biometric_id','=','compensation_fixed_details.biometric_id')
        ->where('job_title_id',26)
        ->where('header_id',$id);

        return $result->get();
    }

    public function createDetails($header_id)
    {   
        //SELECT biometric_id FROM employees WHERE job_title_id = 26;
        $result = $this->model->select('biometric_id')->from('employees')->whereRaw('job_title_id=26')->get();

        foreach($result as $bio){
            $arr = ['biometric_id' => $bio->biometric_id,'header_id' => $header_id];
            $result = $this->model->updateOrCreate($arr,$arr);
        }

    }

}