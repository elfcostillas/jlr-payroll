<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostedPayrollRegisterWeeklyMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\PayrollPeriod';
    protected $rules = [
    	
    ];



    public function getHeaders($period)
    {
        if(is_object($period)){
            $period = $period->id;
        }else {
            $period = $period;
        }
        $result = $this->model->select(DB::raw(
        "
        SUM(ifnull(late,0)) AS late,
        SUM(ifnull(late_eq,0)) AS late_eq,
        SUM(ifnull(under_time,0)) AS under_time,
        SUM(ifnull(over_time,0)) AS over_time,
        SUM(ifnull(night_diff,0)) AS night_diff,
        SUM(ifnull(night_diff_ot,0)) AS night_diff_ot,
        
        sum(ifnull(restday_hrs,0)) as restday_hrs,
        sum(ifnull(restday_ot,0)) as restday_ot,
        sum(ifnull(restday_nd,0)) as restday_nd,
        sum(ifnull(restday_ndot,0)) as restday_ndot,

        sum(ifnull(reghol_pay,0)) as reghol_pay,
        sum(ifnull(reghol_hrs,0)) as reghol_hrs,
        sum(ifnull(reghol_ot,0)) as reghol_ot,
        sum(ifnull(reghol_rd,0)) as reghol_rd,
        sum(ifnull(reghol_rdot,0)) as reghol_rdot,
        sum(ifnull(reghol_nd,0)) as reghol_nd,
        sum(ifnull(reghol_rdnd,0)) as reghol_rdnd,
        sum(ifnull(reghol_ndot,0)) as reghol_ndot,
        sum(ifnull(reghol_rdndot,0)) as reghol_rdndot,

        sum(ifnull(sphol_pay,0)) as sphol_pay,
        sum(ifnull(sphol_hrs,0)) as sphol_hrs,
        sum(ifnull(sphol_ot,0)) as sphol_ot,
        sum(ifnull(sphol_rd,0)) as sphol_rd,
        sum(ifnull(sphol_rdot,0)) as sphol_rdot,
        sum(ifnull(sphol_nd,0)) as sphol_nd,
        sum(ifnull(sphol_rdnd,0)) as sphol_rdnd,
        sum(ifnull(sphol_ndot,0)) as sphol_ndot,
        sum(ifnull(sphol_rdndot,0)) as sphol_rdndot,

        sum(ifnull(dblhol_pay,0)) as dblhol_pay,
        sum(ifnull(dblhol_hrs,0)) as dblhol_hrs,
        sum(ifnull(dblhol_ot,0)) as dblhol_ot,
        sum(ifnull(dblhol_rd,0)) as dblhol_rd,
        sum(ifnull(dblhol_rdot,0)) as dblhol_rdot,
        sum(ifnull(dblhol_rdnd,0)) as dblhol_rdnd,
        sum(ifnull(dblhol_nd,0)) as dblhol_nd,
        sum(ifnull(dblhol_ndot,0)) as dblhol_ndot,
        sum(ifnull(dblhol_rdndot,0)) as dblhol_rdndot"))
        ->from('payroll_period_weekly')
        ->join('edtr',function($join){
            //$join->whereBetween('dtr_date',['payroll_period_weekly.date_from','payroll_period_weekly.date_to']);
            $join->whereRaw(DB::raw(" dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to "));
        })
        ->where('payroll_period_weekly.id',$period);

        return $result->first();
    }

    public function getColHeaders()
    {   
        //SELECT var_name,col_label FROM payreg_header;
       
        $result = $this->model->select('var_name','col_label')->from('payreg_header2')->orderBy('sort_no','asc');
        return $result->get();

    }

    public function getDTR($biometric_id,$period_id){

    }

    function getData($loc,$period_id,$div,$dept,$bio){
        // dd($loc,$period,$div,$dept,$bio);
        if($loc > 0){
            $and = " and weekly_tmp_locations.loc_id = $loc ";
        }
        else{
            $and = "";
            //AND employees.pay_type = 3
        }

        $qry = "SELECT DISTINCT employees.biometric_id,employee_name,lastname,firstname,basic_salary,retired,job_title_name,dept_name
        FROM employees INNER JOIN edtr ON employees.biometric_id = edtr.biometric_id
        INNER JOIN employee_names_vw ON employees.biometric_id = employee_names_vw.biometric_id
        INNER JOIN payroll_period_weekly ON dtr_date BETWEEN date_from AND date_to
        LEFT JOIN job_titles ON job_title_id = job_titles.id
        LEFT JOIN departments ON employees.dept_id = departments.id
        LEFT JOIN weekly_tmp_locations on weekly_tmp_locations.biometric_id = employees.biometric_id and weekly_tmp_locations.period_id  = payroll_period_weekly.id 
        WHERE payroll_period_weekly.id = $period_id  $and
        AND (time_in IS NOT NULL AND time_out IS NOT NULL AND time_in != '00:00' AND time_out != '00:00' )
        ORDER BY lastname,firstname";

        $result = DB::select($qry);
        
        foreach($result as $emp){
            
            $dtr_qry = "SELECT edtr.* FROM edtr INNER JOIN payroll_period_weekly ON dtr_date BETWEEN date_from AND date_to
            WHERE payroll_period_weekly.id = $period_id AND biometric_id = $emp->biometric_id
            ORDER BY dtr_date";

            $dtr = DB::select($dtr_qry);

            $emp->dtr = $dtr;
        }

        return $result;
    }

    public function getLocation($id)
    {
        if($id == 0)
        {
            return "All locations";
        }else {
            $location = DB::table('locations')->where('id',$id)->first();
            return $location->location_name;
        }
    }

    public function unpost($period_id)
    {
        $posting_info = DB::table('posting_info')->where([
            ['trans_type','=','weekly'],
            ['period_id','=',$period_id]
        ])->delete();

        $posted = DB::table('payrollregister_posted_weekly')
                        ->where('period_id','=',$period_id)
                        ->delete();

        return array('success'=>'Payroll Period unposted successfully.');
        
    }

    public function getPostedDataforRCBC($period_id)
    {
        $result = DB::table('payrollregister_posted_weekly')->select(DB::raw('employee_names_vw.employee_name,employees.bank_acct,payrollregister_posted_weekly.net_pay'))
        ->leftJoin('employees','payrollregister_posted_weekly.biometric_id','=','employees.biometric_id')
        ->leftJoin('employee_names_vw','payrollregister_posted_weekly.biometric_id','=','employee_names_vw.biometric_id')
        ->where('period_id','=',$period_id);


        return $result->get();

    }
}
    /*
    SELECT employee_names_vw.employee_name,employees.bank_acct,payrollregister_posted_weekly.net_pay FROM payrollregister_posted_weekly 
    LEFT JOIN employees ON payrollregister_posted_weekly.biometric_id = employees.biometric_id
    LEFT JOIN employee_names_vw ON payrollregister_posted_weekly.biometric_id = employee_names_vw.biometric_id
    WHERE period_id = 23
    ORDER BY employees.lastname,employees.firstname;
    */

?>