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
        $result = $this->model->select(DB::raw("employee_names_vw.*,line_id,fy_year,IFNULL(vacation_leave,0) vacation_leave,IFNULL(sick_leave,0) sick_leave,IFNULL(summer_vacation_leave,0) summer_vacation_leave,IFNULL(paternity_leave,0) paternity_leave"))
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
        $qry = "  SELECT employees.biometric_id,lastname,firstname,suffixname,IFNULL(vacation_leave,0) vacation_leave,IFNULL(sick_leave,0) sick_leave,IFNULL(summer_vacation_leave,0) summer_vacation_leave,IFNULL(paternity_leave,0) paternity_leave,
        IFNULL(VL_PAY,0) VL_PAY,IFNULL(SL_PAY,0) SL_PAY,IFNULL(SVL_PAY,0) SVL_PAY,IFNULL(MP_PAY,0) MP_PAY
        FROM employees LEFT JOIN leave_credits ON leave_credits.biometric_id = employees.biometric_id
        LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS VL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end'  AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type in ('VL','EL')
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS vl ON employees.biometric_id = vl.biometric_id
       LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS SL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type = 'SL'
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS sl ON employees.biometric_id = sl.biometric_id
      LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS SVL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type = 'SVL'
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS svl ON employees.biometric_id = svl.biometric_id
      LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS MP_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type = 'MP'
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS mp ON employees.biometric_id = mp.biometric_id
        WHERE leave_credits.fy_year = $year;";

        $result = DB::select($qry);

        return $result;

        /*
        SELECT biometric_id,ROUND(SUM(with_pay)/8,2) 
        FROM leave_request_header INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id WHERE
        leave_date BETWEEN '2023-01-01' AND '2023-12-31'
        AND with_pay > 0
        AND is_canceled = 'N'
        AND leave_type = 'VL'
        GROUP BY biometric_id

SELECT biometric_id,ROUND(SUM(with_pay)/8,2) 
        FROM leave_request_header INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id WHERE
        leave_date BETWEEN '2023-01-01' AND '2023-12-31'
        AND with_pay > 0
        AND is_canceled = 'N'
        AND leave_type = 'SL'
        GROUP BY biometric_id
        */
    }

    public function showLeaves($biometric_id,$from,$to)
    {
        $qry = "SELECT leave_date,leave_type,remarks,ROUND(with_pay/8,2) with_pay,ROUND(without_pay/8,2) without_pay FROM leave_request_header INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
        WHERE leave_request_header.biometric_id = $biometric_id
        AND leave_date BETWEEN '$from' AND '$to'
        and document_status = 'POSTED'  and leave_request_header.acknowledge_status = 'Approved'
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

    function getBalance($year,$start,$end,$biometric_id){
        $qry = "SELECT employees.biometric_id,lastname,firstname,suffixname,IFNULL(vacation_leave,0) vacation_leave,IFNULL(sick_leave,0) sick_leave,IFNULL(summer_vacation_leave,0) summer_vacation_leave,
        IFNULL(VL_PAY,0) VL_PAY,IFNULL(SL_PAY,0) SL_PAY,IFNULL(SVL_PAY,0) SVL_PAY
        FROM employees LEFT JOIN leave_credits ON leave_credits.biometric_id = employees.biometric_id
        LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS VL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end'  AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type in ('VL','EL')
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS vl ON employees.biometric_id = vl.biometric_id
       LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS SL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type = 'SL'
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS sl ON employees.biometric_id = sl.biometric_id
       LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS SVL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type = 'SVL'
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS svl ON employees.biometric_id = svl.biometric_id
        WHERE leave_credits.fy_year = $year and employees.biometric_id = $biometric_id;";

        $result = DB::select($qry);

        return $result;

    }


}

/*

SELECT employees.biometric_id,lastname,firstname,suffixname,IFNULL(vacation_leave,0) vacation_leave,IFNULL(sick_leave,0) sick_leave,
        IFNULL(VL_PAY,0) VL_PAY,IFNULL(SL_PAY,0) SL_PAY
        FROM employees LEFT JOIN leave_credits ON leave_credits.biometric_id = employees.biometric_id
        LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS VL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end'  AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type in ('VL','EL')
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS vl ON employees.biometric_id = vl.biometric_id
       LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS SL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type = 'SL'
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS sl ON employees.biometric_id = sl.biometric_id
        WHERE leave_credits.fy_year = $year and employees.biometric_id = $biometric_id;

SELECT employees.biometric_id,lastname,firstname,suffixname,IFNULL(vacation_leave,0) vacation_leave,IFNULL(sick_leave,0) sick_leave,IFNULL(summer_vacation_leave,0) summer_vacation_leave,
        IFNULL(VL_PAY,0) VL_PAY,IFNULL(SL_PAY,0) SL_PAY,IFNULL(SVL_PAY,0) SVL_PAY
        FROM employees LEFT JOIN leave_credits ON leave_credits.biometric_id = employees.biometric_id
        LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS VL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '2024-01-01' AND '2024-12-31'  AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type in ('VL','EL')
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS vl ON employees.biometric_id = vl.biometric_id
       LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS SL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '2024-01-01' AND '2024-12-31' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type = 'SL'
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS sl ON employees.biometric_id = sl.biometric_id
       LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS SVL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '2024-01-01' AND '2024-12-31' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type = 'SVL'
          AND leave_request_header.received_by IS NOT NULL
          GROUP BY biometric_id
      ) AS svl ON employees.biometric_id = svl.biometric_id
        WHERE leave_credits.fy_year = 2024 and employees.biometric_id = 834;


SELECT * FROM employees LEFT JOIN departments ON employees.dept_id = departments.id
LEFT JOIN divisions ON divisions.id = division_id
LEFT JOIN job_titles ON job_titles.id = employees.job_title_id
*/