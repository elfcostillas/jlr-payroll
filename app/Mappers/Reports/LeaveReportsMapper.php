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
        WHERE is_canceled = 'N' AND  document_status = 'POSTED' AND received_by IS NOT NULL and acknowledge_status = 'Approved'
        AND leave_date between '".$from."' and '".$to."'
        ORDER BY leave_date ASC,employee_name;";

        $result = DB::select($query);

        return $result;    
    } 

    public function getLeavesByPayType($from,$to)
    {
        //SELECT id, pay_description FROM emp_pay_types;

        $paytype = DB::table('emp_pay_types')->select('id','pay_description')->get();

        foreach($paytype as $type)
        {
            $query = "SELECT leave_request_header.biometric_id,employee_name,leave_request_header.remarks,leave_type,DATE_FORMAT(leave_date,'%m/%d/%Y') AS mask_leave_date,leave_request_detail.* FROM leave_request_header 
            INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
            INNER JOIN employee_names_vw ON leave_request_header.biometric_id = employee_names_vw.biometric_id
            INNER JOIN employees on leave_request_header.biometric_id = employees.biometric_id
            WHERE is_canceled = 'N' AND  document_status = 'POSTED' AND received_by IS NOT NULL and acknowledge_status = 'Approved'

            AND leave_date between '".$from."' and '".$to."'
            AND pay_type = ".$type->id."
            ORDER BY leave_date ASC,employee_name;";
    
            $result = DB::select($query);

            $type->emps = $result;
        }

        return $paytype;    
    } 

    public function getLeavesSummary($from,$to)
    {
        $query = "SELECT leave_request_header.biometric_id,employee_name,SUM(IFNULL(with_pay,0)) AS with_pay,SUM(IFNULL(without_pay,0)) AS without_pay FROM leave_request_header 
        INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
        INNER JOIN employee_names_vw ON leave_request_header.biometric_id = employee_names_vw.biometric_id
        WHERE is_canceled = 'N' AND  document_status = 'POSTED' AND received_by IS NOT NULL and acknowledge_status = 'Approved'
        AND leave_date between '$from' and '$to'
        GROUP BY leave_request_header.biometric_id
        ORDER BY leave_date ASC,employee_name;";
        //AND leave_type != 'UL'
        $result = DB::select($query);   

        return $result;  
    }

    public function getLeaveSummaryByEmployee($from,$to,$type)
    {
        $year = Carbon::createFromFormat('Y-m-d',$from)->format('Y');
        $start = $year.'-01-01';

        if($type=='nonconfi'){
            $level = 'and emp_level >= 5 ';
        }else {
            $level = 'and emp_level < 5 ';
        }
        $employees = "SELECT leave_request_header.biometric_id,employee_name FROM leave_request_header 
        INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
        INNER JOIN employee_names_vw ON leave_request_header.biometric_id = employee_names_vw.biometric_id
        inner join employees on leave_request_header.biometric_id = employees.biometric_id
        WHERE is_canceled = 'N' AND  document_status = 'POSTED' AND received_by IS NOT NULL and acknowledge_status = 'Approved'
        AND leave_date between '$from' and '$to'  $level and pay_type != 3
        GROUP BY leave_request_header.biometric_id
        ORDER BY employee_name ASC,leave_date ASC;
        ";
    //AND leave_type != 'UL'
        $result = DB::select($employees);

        foreach($result as $emp)
        {
            $leaves = $query = "SELECT leave_request_header.biometric_id,employee_name,remarks,leave_type,DATE_FORMAT(leave_date,'%m/%d/%Y') AS mask_leave_date,leave_request_detail.* FROM leave_request_header
            INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
            INNER JOIN employee_names_vw ON leave_request_header.biometric_id = employee_names_vw.biometric_id
            WHERE is_canceled = 'N' AND document_status = 'POSTED' AND received_by IS NOT NULL and acknowledge_status = 'Approved'
            AND leave_date between '$from' and '$to' AND leave_request_header.biometric_id = '$emp->biometric_id'

            ORDER BY leave_date ASC,employee_name;";
    //AND leave_type != 'UL'
            $leave =  DB::select($leaves);
            
            foreach($leave as $l){
                $bal = $this->getBalance($year,$start,$l->leave_date,$emp->biometric_id);
              
                $l->bal = $bal;
            }
            $emp->leaves = $leave;
        }

        return $result;
    }

    function getBalance($year,$start,$end,$biometric_id){
    //     $qry = "  SELECT employees.biometric_id,lastname,firstname,suffixname,IFNULL(vacation_leave,0) vacation_leave,IFNULL(sick_leave,0) sick_leave,
    //     IFNULL(VL_PAY,0) VL_PAY,IFNULL(SL_PAY,0) SL_PAY
    //     FROM employees LEFT JOIN leave_credits ON leave_credits.biometric_id = employees.biometric_id
    //     LEFT JOIN (
    //       SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS VL_PAY FROM leave_request_header 
    //           INNER JOIN leave_request_detail ON leave_request_header.id = header_id
    //       WHERE leave_date BETWEEN '$start' AND '$end'  AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
    //       AND with_pay > 0
    //       AND is_canceled = 'N'
    //       AND leave_type in ('VL','EL')
    //       AND leave_request_header.received_by IS NOT NULL
    //       GROUP BY biometric_id
    //   ) AS vl ON employees.biometric_id = vl.biometric_id
    //    LEFT JOIN (
    //       SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS SL_PAY FROM leave_request_header 
    //           INNER JOIN leave_request_detail ON leave_request_header.id = header_id
    //       WHERE leave_date BETWEEN '$start' AND '$end' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
    //       AND with_pay > 0
    //       AND is_canceled = 'N'
    //       AND leave_type = 'SL'
    //       AND leave_request_header.received_by IS NOT NULL
    //       GROUP BY biometric_id
    //   ) AS sl ON employees.biometric_id = sl.biometric_id
    //     WHERE leave_credits.fy_year = $year and employees.biometric_id = $biometric_id;";

        $qry = "SELECT employees.biometric_id,lastname,firstname,suffixname,IFNULL(vacation_leave,0) vacation_leave,IFNULL(sick_leave,0) sick_leave,
        IFNULL(VL_PAY,0) VL_PAY,IFNULL(SL_PAY,0) SL_PAY
    from employees  
    LEFT JOIN (
          SELECT biometric_id,ROUND(SUM(with_pay)/8,2) AS VL_PAY FROM leave_request_header 
              INNER JOIN leave_request_detail ON leave_request_header.id = header_id
          WHERE leave_date BETWEEN '$start' AND '$end' AND leave_request_header.acknowledge_status = 'Approved' AND document_status = 'POSTED'
          AND with_pay > 0
          AND is_canceled = 'N'
          AND leave_type in ('VL','EL')
          AND leave_request_header.received_by IS NOT NULL
          AND biometric_id = $biometric_id
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
          AND biometric_id = $biometric_id
          GROUP BY biometric_id
      ) AS sl ON employees.biometric_id = sl.biometric_id
      left join (
        select * from leave_credits where biometric_id = $biometric_id and fy_year = $year
      ) as leave_credits on leave_credits.biometric_id = employees.biometric_id
      where employees.biometric_id = $biometric_id;";

        $result = DB::select($qry);

        return $result;

    }

    public function getDivisions()
    {
        //
        //SELECT departments.dept_code FROM employees INNER JOIN departments ON employees.dept_id = departments.id
        
        //INNER JOIN `employee_names_vw` ON `employee_names_vw`.`biometric_id` = `edtr`.`biometric_id` 
        $qa = $this->model->select(DB::raw("101 AS id,'Quality Assurance' div_name")); // DB::select("SELECT 101 AS id,'QA' div_name");
        
        $result = $this->model->select('id','div_name')->from('divisions')->union($qa);

        $divisions = $result->get();

        foreach($divisions as $div)
        {
            //select estatus_desc from employees left join emp_emp_stat on employees.employee_stat = emp_emp_stat.id
            if($div->id != 101){
                $emp = $this->model->select('employees.biometric_id','employee_names_vw.employee_name','departments.dept_code','estatus_desc',DB::raw('ifnull(date_format(date_hired,"%m/%d/%Y"),"wala gi set ni maria mae") as date_hired'))
                    ->from('employees')
                    ->join('employee_names_vw','employee_names_vw.biometric_id','=','employees.biometric_id')
                    ->leftJoin('departments','employees.dept_id','=','departments.id')
                    ->leftJoin('emp_emp_stat','employees.employee_stat','=','emp_emp_stat.id')
                    ->where('employees.division_id',$div->id)
                    ->where('employees.exit_status',1)
                    ->where('employees.pay_type','!=',3)
                    ->where('employees.dept_id','!=',5)
                    ->orderBy('employees.dept_id','asc')
                    ->orderBy('lastname','asc')
                    ->orderBy('firstname','asc')
                    ->get();
            }else{
                $emp = $this->model->select('employees.biometric_id','employee_names_vw.employee_name','departments.dept_code','estatus_desc',DB::raw('ifnull(date_format(date_hired,"%m/%d/%Y"),"wala gi set ni maria mae") as date_hired'))
                    ->from('employees')
                    ->join('employee_names_vw','employee_names_vw.biometric_id','=','employees.biometric_id')
                    ->where('employees.division_id',2)
                    ->leftJoin('departments','employees.dept_id','=','departments.id')
                    ->leftJoin('emp_emp_stat','employees.employee_stat','=','emp_emp_stat.id')
                    ->where('employees.exit_status',1)
                    ->where('employees.pay_type','!=',3)
                    ->where('employees.dept_id','=',5)
                    ->orderBy('employees.dept_id','asc')
                    ->orderBy('lastname','asc')
                    ->orderBy('firstname','asc')
                    ->get();
            }
           
            $div->emp = $emp;
        }

        return $divisions;
    }

    public function getData($start,$end)
    {

        if($start == '2023-01-01'){
            $sub_qry = "SELECT employees.biometric_id, tardy_count as late_count,0 as in_minutes FROM employees 
            INNER JOIN manual_tardy ON employees.biometric_id = manual_tardy.biometric_id
            WHERE  emp_level >= 3
            and job_title_id != 12";
        }
        else{
            // $sub_qry = " SELECT employees.biometric_id,COUNT(dtr_date) late_count,SUM((TIME_TO_SEC(edtr.time_in)- TIME_TO_SEC(work_schedules.time_in))/60) AS in_minutes FROM edtr 
            // INNER JOIN employees ON edtr.biometric_id = employees.biometric_id
            // INNER JOIN work_schedules ON schedule_id = work_schedules.id
            // INNER JOIN employee_names_vw ON employee_names_vw.biometric_id = edtr.biometric_id
            // AND (
            //     (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) && TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am)) OR
            //     (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) && TIME_TO_SEC(work_schedules.time_in) <= TIME_TO_SEC(work_schedules.time_out) )
            //     )
            // AND dtr_date BETWEEN '$start' AND '$end'
            // and emp_level >= 3
            // and job_title_id != 12
            // GROUP BY employees.biometric_id,lastname,firstname
            // ORDER BY lastname,dtr_date";
            //SELECT departments.dept_code FROM employees INNER JOIN departments ON employees.dept_id = departments.id

            $sub_qry = "SELECT employees.biometric_id,COUNT(dtr_date) late_count,SUM((TIME_TO_SEC(edtr.time_in)- TIME_TO_SEC(work_schedules.time_in))/60) AS in_minutes FROM edtr 
            INNER JOIN employees ON edtr.biometric_id = employees.biometric_id
            INNER JOIN work_schedules ON schedule_id = work_schedules.id
            INNER JOIN employee_names_vw ON employee_names_vw.biometric_id = edtr.biometric_id
          
            LEFT JOIN (select holiday_date,location_id,holiday_type from holidays inner join holiday_location on holidays.id = holiday_location.holiday_id) as holidays on dtr_date = holidays.holiday_date and holidays.location_id = employees.location_id
            
            WHERE (
                (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) && TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am)) OR
                (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) && TIME_TO_SEC(work_schedules.time_in) <= TIME_TO_SEC(work_schedules.time_out) )
                )
            AND dtr_date BETWEEN '$start' AND '$end'
            and emp_level >= 3
            and job_title_id != 12
            and holiday_type is null 
            GROUP BY employees.biometric_id,lastname,firstname
            ORDER BY lastname";
        }

        $qry = "SELECT employees.biometric_id,IFNULL(sl_count,0) sl_count,IFNULL(vl_count,0) vl_count,IFNULL(el_count,0) el_count,IFNULL(ut_count,0) ut_count,
        IFNULL(bl_count,0) bl_count,IFNULL(mp_count,0) mp_count,IFNULL(o_count,0) o_count,IFNULL(svl_count,0) svl_count,IFNULL(late_count,0) late_count,IFNULL(in_minutes,0) in_minutes
        FROM employees LEFT JOIN 
        (
        SELECT biometric_id,COUNT(leave_date) AS sl_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL and leave_request_header.acknowledge_status = 'Approved'
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'SL'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS sl ON employees.biometric_id = sl.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS vl_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL and leave_request_header.acknowledge_status = 'Approved'
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'VL'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        )
        AS vl ON employees.biometric_id = vl.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS el_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL and leave_request_header.acknowledge_status = 'Approved'
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'EL'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS el ON employees.biometric_id = el.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS ut_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL and leave_request_header.acknowledge_status = 'Approved'
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'UT'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS ut ON employees.biometric_id = ut.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS bl_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL and leave_request_header.acknowledge_status = 'Approved'
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'BL'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS bl ON employees.biometric_id = bl.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS mp_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL and leave_request_header.acknowledge_status = 'Approved'
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'MP'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS mp ON employees.biometric_id = mp.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS o_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL and leave_request_header.acknowledge_status = 'Approved'
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'O'
        AND leave_date BETWEEN '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS o ON employees.biometric_id = o.biometric_id
        LEFT JOIN (
        SELECT biometric_id,COUNT(leave_date) AS svl_count FROM leave_request_header INNER JOIN leave_request_detail ON id = header_id 
        WHERE leave_request_header.received_by IS NOT NULL and leave_request_header.acknowledge_status = 'Approved'
        AND (with_pay IS NOT NULL AND without_pay IS NOT NULL)
        AND leave_type = 'SVL'
        AND leave_date BETWEEN  '$start' AND '$end'
        AND (IFNULL(with_pay,0) + IFNULL(without_pay,0)) > 0
        GROUP BY biometric_id
        ) AS svl ON employees.biometric_id = svl.biometric_id
        LEFT JOIN (
            $sub_qry
       ) AS tardy ON employees.biometric_id = tardy.biometric_id
        WHERE pay_type != 3";

        $result = DB::select($qry);

        return $result;
    }   
}