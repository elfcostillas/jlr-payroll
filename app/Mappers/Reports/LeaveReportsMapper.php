<?php

namespace App\Mappers\Reports;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveReportsMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\Location';
    protected $rules = [
    	
    ];

    public function getLeavesfromRange($from,$to)
    {
        $query = "SELECT leave_request_header.biometric_id,employee_name,remarks,leave_type,DATE_FORMAT(leave_date,'%m/%d/%Y') AS mask_leave_date,leave_request_detail.* FROM leave_request_header 
        INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
        INNER JOIN employee_names_vw ON leave_request_header.biometric_id = employee_names_vw.biometric_id
        WHERE is_canceled = 'N' AND  document_status = 'POSTED' AND received_by IS NOT NULL
        AND leave_date between '".$from."' and '".$to."'
        ORDER BY leave_date ASC,employee_name;";

        $result = DB::select($query);

        return $result;    
    } 

    public function getLeavesSummary($from,$to)
    {
        $query = "SELECT leave_request_header.biometric_id,employee_name,SUM(IFNULL(with_pay,0)) AS with_pay,SUM(IFNULL(without_pay,0)) AS without_pay FROM leave_request_header 
        INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
        INNER JOIN employee_names_vw ON leave_request_header.biometric_id = employee_names_vw.biometric_id
        WHERE is_canceled = 'N' AND  document_status = 'POSTED' AND received_by IS NOT NULL AND leave_type != 'UL'
        AND leave_date between '$from' and '$to'
        GROUP BY leave_request_header.biometric_id
        ORDER BY leave_date ASC,employee_name;";

        $result = DB::select($query);

        return $result;  
    }

    public function getLeaveSummaryByEmployee($from,$to)
    {
        $employees = "SELECT leave_request_header.biometric_id,employee_name FROM leave_request_header 
        INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
        INNER JOIN employee_names_vw ON leave_request_header.biometric_id = employee_names_vw.biometric_id
        WHERE is_canceled = 'N' AND  document_status = 'POSTED' AND received_by IS NOT NULL AND leave_type != 'UL'
        AND leave_date between '$from' and '$to'
        GROUP BY leave_request_header.biometric_id
        ORDER BY leave_date ASC,employee_name;
        ";

        $result = DB::select($employees);

        foreach($result as $emp)
        {
            $leaves = $query = "SELECT leave_request_header.biometric_id,employee_name,remarks,leave_type,DATE_FORMAT(leave_date,'%m/%d/%Y') AS mask_leave_date,leave_request_detail.* FROM leave_request_header
            INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
            INNER JOIN employee_names_vw ON leave_request_header.biometric_id = employee_names_vw.biometric_id
            WHERE is_canceled = 'N' AND document_status = 'POSTED' AND received_by IS NOT NULL AND leave_type != 'UL'
            AND leave_date between '$from' and '$to' AND leave_request_header.biometric_id = '$emp->biometric_id'

            ORDER BY leave_date ASC,employee_name;";

            $emp->leaves = DB::select($leaves);
        }

        return $result;
    }

    public function getDivisions()
    {

        //INNER JOIN `employee_names_vw` ON `employee_names_vw`.`biometric_id` = `edtr`.`biometric_id` 
        $result = $this->model->select('id','div_name')->from('divisions');

        $divisios = $result->get();

        foreach($divisios as $div)
        {
            $emp = $this->model->select('employees.biometric_id','employee_names_vw.employee_name')
                                ->from('employees')
                                ->join('employee_names_vw','employee_names_vw.biometric_id','=','employees.biometric_id')
                                ->where('employees.division_id',$div->id)
                                ->where('employees.exit_status',1)
                                ->where('employees.pay_type','!=',3)
                                ->orderBy('lastname','asc')
                                ->orderBy('firstname','asc')
                                ->get();
            $div->emp = $emp;
        }

        return $divisios;
    }

    public function getData($start,$end)
    {
        $qry = "SELECT employees.biometric_id,IFNULL(sl_count,0) sl_count,IFNULL(vl_count,0) vl_count,IFNULL(el_count,0) el_count,IFNULL(ut_count,0) ut_count,
        IFNULL(bl_count,0) bl_count,IFNULL(mp_count,0) mp_count,IFNULL(o_count,0) o_count,IFNULL(svl_count,0) svl_count,IFNULL(late_count,0) late_count,IFNULL(in_minutes,0) in_minutes
        FROM employees LEFT JOIN 
        (
        SELECT biometric_id,COUNT(leave_date) AS sl_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'SL'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS sl ON employees.biometric_id = sl.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS vl_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'VL'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        )
        AS vl ON employees.biometric_id = vl.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS el_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'EL'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS el ON employees.biometric_id = el.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS ut_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'UT'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS ut ON employees.biometric_id = ut.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS bl_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'BL'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS bl ON employees.biometric_id = bl.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS mp_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'MP'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS mp ON employees.biometric_id = mp.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS o_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'O'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS o ON employees.biometric_id = o.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS svl_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'SVL'
        AND leave_date BETWEEN  '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS svl ON employees.biometric_id = svl.biometric_id
        LEFT JOIN (
        SELECT employees.biometric_id,COUNT(dtr_date) late_count,SUM((TIME_TO_SEC(edtr.time_in)- TIME_TO_SEC(work_schedules.time_in))/60) AS in_minutes FROM edtr 
        INNER JOIN employees ON edtr.biometric_id = employees.biometric_id
        INNER JOIN work_schedules ON schedule_id = work_schedules.id
        INNER JOIN employee_names_vw ON employee_names_vw.biometric_id = edtr.biometric_id
        AND (
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) && TIME_TO_SEC(edtr.time_in) <= TIME_TO_SEC(work_schedules.out_am)) OR
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) && TIME_TO_SEC(work_schedules.time_in) <= TIME_TO_SEC(work_schedules.time_out) )
            )
        AND dtr_date BETWEEN '$start' AND '$end'
        and emp_level >= 3
        and job_title_id != 12
        GROUP BY employees.biometric_id,lastname,firstname
        ORDER BY lastname,dtr_date) AS tardy ON employees.biometric_id = tardy.biometric_id
        WHERE pay_type != 3";

        $result = DB::select($qry);

        return $result;
    }   
}