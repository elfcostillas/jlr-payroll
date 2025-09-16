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
        //SELECT * FROM job_titles WHERE ;
        $driver = $this->model->select('id')->from('job_titles')->whereRaw("job_title_name LIKE '%Driver%'")->orWhereRaw("job_title_name LIKE '%Operator%'");
       
        $result = $this->model->select(DB::raw("employee_names_vw.*,compensation_fixed_details.line_id,IFNULL(compensation_fixed_details.total_amount,0.00) AS total_amount,header_id"))
        ->from('employee_names_vw')
        ->join('employees','employees.biometric_id','=','employee_names_vw.biometric_id')
        ->leftJoin('compensation_fixed_details','employee_names_vw.biometric_id','=','compensation_fixed_details.biometric_id')
        //->where('job_title_id',26)
        ->whereIn('job_title_id',$driver->pluck('id'))
        ->where('header_id',$id);

        return $result->get();
    }

    public function createDetails($header_id)
    {   
        //SELECT biometric_id FROM employees WHERE job_title_id = 26;
        $driver = $this->model->select('id')->from('job_titles')->whereRaw("job_title_name LIKE '%Driver%'")->orWhereRaw("job_title_name LIKE '%Operator%'");
        //$result = $this->model->select('biometric_id')->from('employees')->whereRaw('job_title_id=26')->get();
        $result = $this->model->select('biometric_id')
                                ->from('employees')
                                ->whereIn('job_title_id',$driver->pluck('id'))
                                ->get();

        foreach($result as $bio){
            $arr = ['biometric_id' => $bio->biometric_id,'header_id' => $header_id];
            $result = $this->model->updateOrCreate($arr,$arr);
        }

    }

}