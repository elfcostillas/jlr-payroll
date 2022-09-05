<?php

namespace App\Mappers\Accounts;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveRequestHeaderMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Accounts\LeaveRequestHeader';
    protected $rules = [
    	
    ];

    public function list($filter)
    {	
		$user = Auth::user();
        $result = $this->model->select(DB::raw("
					leave_request_header.id,
					leave_request_header.biometric_id,
					concat(ifnull(employees.lastname,''),', ',ifnull(employees.firstname,'')) as requesting_emp,
					leave_request_type.leave_type_code,
					leave_request_header.remarks,
					document_status,
					acknowledge_status,
					acknowledge_time,
					concat(ifnull(approver.lastname,''),', ',ifnull(approver.firstname,'')) as approver_emp,
					acknowledge_time,
					concat(ifnull(hr_staff.lastname,''),', ',ifnull(hr_staff.firstname,'')) as hr_emp,
					received_time
					"))
				->from('leave_request_header')
				->join('employees','leave_request_header.biometric_id','=','employees.biometric_id')
				->leftJoin('divisions','leave_request_header.division_id','=','divisions.id')
				->leftJoin('departments','leave_request_header.dept_id','=','departments.id')
				->leftJoin('job_titles','leave_request_header.job_title_id','=','job_titles.id')
				->leftJoin('leave_request_type','leave_type_code','=','leave_type')
				->leftJoin('employees AS approver','approver.biometric_id','=','acknowledge_by')
				->leftJoin('employees AS hr_staff','hr_staff.biometric_id','=','received_by')
				->where('encoded_by',$user->id);

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('leave_request_header.id','DESC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }

	public function searchEmployee($filter)
	{	
		
		$result = $this->model->select(DB::raw("biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS employee_name,employees.dept_id,employees.division_id,employees.job_title_id,job_title_name,dept_name,div_name"))
					->from('employees')
					->leftJoin('departments', 'departments.id','=', 'employees.dept_id')
					->leftJoin('divisions', 'divisions.id','=', 'employees.division_id')
					->leftJoin('job_titles', 'job_titles.id','=', 'job_title_id');
	
		if($filter!=null){
			if(array_key_exists('filters',$filter)){
				foreach($filter['filters'] as $f)
				{
					$result->where('lastname','like','%'.$f['value'].'%')
					->orWhere('firstname','like','%'.$f['value'].'%');
				}
			}
		}

		return $result->get();
		
	}

	public function header($id)
	{
		$result = $this->model->select(DB::raw("leave_request_header.id,
					leave_request_header.biometric_id,
					leave_request_header.reliever_id,
					date_from,
					date_to,
					leave_type,
					leave_request_header.remarks,
					employees.division_id,
					div_name,
					employees.dept_id,
					dept_name,
					employees.job_title_id,
					job_title_name,
					encoded_on,
					encoded_by,
					request_date,
					acknowledge_status,
					acknowledge_time,
					acknowledge_by,
					received_by,
					received_time,
					document_status
					"))
				->from('leave_request_header')
				->join('employees','leave_request_header.biometric_id','=','employees.biometric_id')
				->leftJoin('divisions','leave_request_header.division_id','=','divisions.id')
				->leftJoin('departments','leave_request_header.dept_id','=','departments.id')
				->leftJoin('job_titles','leave_request_header.job_title_id','=','job_titles.id')
				->where('leave_request_header.id',$id);

		return $result->first();
	}

}

/*

SELECT * FROM leave_request_header 
INNER JOIN employees ON leave_request_header.biometric_id = employees.biometric_id
LEFT JOIN divisions ON leave_request_header.division_id = divisions.id
LEFT JOIN departments ON leave_request_header.dept_id = departments.id
LEFT JOIN job_titles ON leave_request_header.job_title_id = job_titles.id
LEFT JOIN leave_request_type ON leave_type_code = leave_type
LEFT JOIN employees AS approver ON approver.biometric_id = acknowledge_by
LEFT JOIN employees AS hr_staff ON hr_staff.biometric_id = received_by
ORDER BY leave_request_header.id


SELECT biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS employee_name,employees.dept_id,employees.division_id,employees.job_title_id,
job_title_name,dept_name,div_name
FROM employees
LEFT JOIN departments ON departments.id = employees.dept_id
LEFT JOIN divisions ON divisions.id = employees.division_id
LEFT JOIN job_titles ON job_titles.id = job_title_id
*/