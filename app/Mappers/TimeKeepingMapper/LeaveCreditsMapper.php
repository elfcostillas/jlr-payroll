<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveCreditsMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\LeaveCredits';
    protected $rules = [
      
    ];

    protected $messages = [
       
    ];

    public function list($filter)
    {
        //$result = $this->model->select('id','date_from','date_to','date_release','man_hours');
        //SELECT `holidays`.id,`holiday_date`,`holiday_remarks`,`holiday_type`,`type_description` FROM holidays INNER JOIN holiday_types ON holidays.`holiday_type` = `holiday_types`.id

        $result = $this->model->select('holidays.id','holiday_date','holiday_remarks','holiday_type','type_description')
                            ->from('holidays')
                            ->join('holiday_types','holidays.holiday_type','=','holiday_types.id');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('id','DESC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }

    public function yearList()
    {   
        //SELECT DISTINCT YEAR(date_from) AS fy FROM payroll_period 
        $result = $this->model->select(DB::raw("DISTINCT YEAR(date_from) AS fy"))->from('payroll_period')
                    ->orderBy("fy","asc");
        return $result->get();            

    }

    public function empList($year)
    {
        $result = $this->model->select(DB::raw("employee_names_vw.*,line_id,fy_year,vacation_leave,sick_leave,summer_vacation_leave,paternity_leave"))
            ->from('employee_names_vw')
            //->leftJoin('leave_credits','leave_credits.biometric_id','=','employee_names_vw.biometric_id')
            ->leftJoin('leave_credits',function($join) use ($year){
                $join->on('leave_credits.biometric_id','=','employee_names_vw.biometric_id');
                $join->where('fy_year',$year);
            })
            ->where('exit_status',1)
            ->where(function($q) use($year) {
                $q->where('fy_year',$year);
                $q->orWhereNull('fy_year');
            })
            ->orderBy('employee_name','asc');

        return $result->get();
    }

    public function process($year,$start,$end)
    {
        $qry = "SELECT employees.biometric_id,lastname,firstname,suffixname,IFNULL(vacation_leave,0) vacation_leave,IFNULL(sick_leave,0) sick_leave,IFNULL(VL_PAY,0) VL_PAY,IFNULL(SL_PAY,0) SL_PAY FROM leave_credits
        INNER JOIN employees ON leave_credits.biometric_id = employees.biometric_id
        LEFT JOIN (
        SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS total,
        CASE
            WHEN leave_type = 'VL' THEN ROUND(SUM(with_pay)/8,2)
            ELSE 0
        END AS VL_PAY,
        CASE
            WHEN leave_type = 'SL' THEN ROUND(SUM(with_pay)/8,2)
            ELSE 0
        END AS SL_PAY
        FROM leave_request_header INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id WHERE
        leave_date BETWEEN '$start' AND '$end'
        AND with_pay > 0
        AND is_canceled = 'N'
        AND leave_type IN ('VL','SL')
        GROUP BY biometric_id
        ) AS consumed ON employees.biometric_id = consumed.biometric_id
        WHERE fy_year = '$year'
        ORDER BY lastname,firstname";


        $result = DB::select($qry);

        return $result;
    }

    public function showLeaves($biometric_id,$from,$to)
    {
        $qry = "SELECT leave_date,leave_type,remarks,ROUND(with_pay/8,2) with_pay,ROUND(without_pay/8,2) without_pay FROM leave_request_header INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
        WHERE leave_request_header.biometric_id = $biometric_id
        AND leave_date BETWEEN '$from' AND '$to'
        and document_status = 'POSTED'
        and received_by IS NOT NULL
        ORDER BY leave_date;";
        
       $result = DB::select($qry);

        return $result;
    }

    public function getEmployeeInfo($biometric_id)
    {
        $result = $this->model->select(DB::raw("CONCAT(lastname,', ',firstname) empname,job_title_name,div_name,dept_name"))
            ->from('employees')
            ->leftJoin('departments','employees.dept_id','=','departments.id')
            ->leftJoin('divisions','divisions.id','=','division_id')
            ->leftJoin('job_titles','job_titles.id','=','employees.job_title_id')
            ->where('biometric_id',$biometric_id);
        return $result->first();
    }

    public function getLeaveCredits($biometric_id,$year)
    {   
        //SELECT vacation_leave,sick_leave FROM leave_credits WHERE fy_year,biometric_id
        $result = $this->model->select('vacation_leave','sick_leave')->from('leave_credits')->where('fy_year',$year)->where('biometric_id',$biometric_id)->first();
        return $result;
    }


}

/*
SELECT * FROM employees LEFT JOIN departments ON employees.dept_id = departments.id
LEFT JOIN divisions ON divisions.id = division_id
LEFT JOIN job_titles ON job_titles.id = employees.job_title_id
*/