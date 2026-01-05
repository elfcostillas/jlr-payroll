<?php

namespace App\Mappers\TimeKeepingMapper;

use App\CustomClass\EmployeeAWOL;
use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class DailyTimeRecordMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\DailyTimeRecord';
    protected $rules = [
        'biometric_id' => 'required|sometimes',
        'dtr_date' => 'required|sometimes',
    ];

    protected $messages = [
        
    ];

    public function dtr($biometric_id,$dtr_from,$date_to)
    {   
        //SELECT * FROM edtr WHERE biometric_id = 847 AND dtr_date BETWEEN '2025-01-01' AND '2025-04-01';
        $result = DB::table('edtr')
            ->select()
            ->where('biometric_id',$biometric_id)
            ->whereBetween('dtr_date',[$dtr_from,$date_to])
            ->get();

        return $result;
    }

    public function employeelist($filter)
    {
        $result = DB::table('employee_names_vw')->where('exit_status',1);

        if($filter['filter']!=null){
            if(array_key_exists('filters',$filter['filter'])){
                foreach($filter['filter']['filters'] as $f)
                {
                    $result->where($f['field'],'like','%'.$f['value'].'%');
                }
            }
		}

        $total = $result->count();

		//$result->limit($filter['pageSize'])->skip($filter['skip']);

		return [
			'total' => $total,
			'data' => $result->get()
		];

    }

    public function prepDTRbyPeriod($period_id,$type)
    {
        $blank_dtr = [];
        $blank_loc = [];
        //LEFT JOIN work_schedules_default ON employees.dept_id = work_schedules_default.dept_id
        if($type=='semi'){

            /*
            $empWithPunch = $this->model->select('edtr_raw.biometric_id','schedule_id')->from('edtr_raw')
            ->join('employees','edtr_raw.biometric_id','=','employees.biometric_id')
            ->join('payroll_period',function($join){
               $join->whereRaw('punch_date between payroll_period.date_from and payroll_period.date_to');
            })
            ->leftJoin('work_schedules_default','employees.dept_id','=','work_schedules_default.dept_id')
            ->whereIn('pay_type',[1,2])
            ->where('exit_status',1)
            ->where('payroll_period.id',$period_id)
            ->distinct()
            ->get();
            */
            $empWithPunch = $this->model->select('employees.biometric_id','sched_mtwtf','sched_sat')->from('employees')
            ->leftJoin('work_schedules_default','employees.dept_id','=','work_schedules_default.dept_id')
            ->whereIn('employees.emp_level',[1,2,3,4,5])
            ->where('exit_status',1)
            //->where('payroll_period.id',$period_id)
            ->distinct()
            ->get();

            $range = $this->model->select('date_from','date_to')->from('payroll_period')->where('payroll_period.id',$period_id)->first();
        }else{
            $empWithPunch = $this->model->select('employees.biometric_id','sched_mtwtf','sched_sat','location_id')->from('employees')
            //->leftJoin('edtr_raw','edtr_raw.biometric_id','=','employees.biometric_id')
            // ->join('payroll_period_weekly',function($join){
            //     //$join->whereBetween('punch_date',['payroll_period_weekly.date_from','payroll_period_weekly.date_to']);
            //     $join->whereRaw('punch_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
            // })
            ->leftJoin('work_schedules_default','employees.dept_id','=','work_schedules_default.dept_id')
            ->where('employees.emp_level',6)
            ->where('exit_status',1)
            //->where('payroll_period_weekly.id',$period_id)
            ->distinct()
            ->get();
           
            $range = $this->model->select('date_from','date_to')->from('payroll_period_weekly')->where('payroll_period_weekly.id',$period_id)->first();
        }
    
        $period = CarbonPeriod::create($range['date_from'],$range['date_to']);

        foreach($empWithPunch as $emp)
        {
            foreach ($period as $date) {
                switch ($date->format('D')){
                    case 'Mon': case 'Tue': case 'Wed': case 'Thu': case 'Fri':
                            $sched = $emp->sched_mtwtf;
                        break;
                        
                    case 'Sat' :
                            $sched = $emp->sched_sat;
                        break;
                    
                    default : 
                            $sched = 0;
                        break;
                }
                array_push($blank_dtr,['biometric_id' => $emp->biometric_id, 'dtr_date' => $date->format('Y-m-d'),'schedule_id' => $sched ]);
            }

            if($type=='weekly'){
                array_push($blank_loc,[
                   
                    'biometric_id' => $emp->biometric_id,
                    'loc_id' => $emp->location_id,
                    'period_id' => $period_id,
                ]);
            }
        }

        $result = DB::table('edtr')->insertOrIgnore($blank_dtr);

        if($type=='weekly'){
            $result = DB::table('weekly_tmp_locations')->insertOrIgnore($blank_loc);
        }
        return $result;
      
    }



    public function empWithDTR($period_id,$filter,$type)
    {

        if($type=='semi'){
            $result = $this->model->select(DB::raw("employees.id,employees.biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) as empname,concat(div_code,' - ',dept_name) as dept_name,div_name"))
                            ->from('edtr')
                            ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                            ->join('payroll_period',function($join){
                                $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
                            })
                            ->join('departments','employees.dept_id','=','departments.id')
                            ->join('divisions','employees.division_id','=','divisions.id')
                            // ->whereIn('employees.emp_level',[1,2])
                            ->where('employees.emp_level','<',6)
                            ->where('exit_status',1)
                            ->where('payroll_period.id',$period_id)
                            ->distinct()
                            ->orderBy('empname','ASC');
                            // departments ON employees.dept_id = departments.id 
        }else{
           
            $result = $this->model->select(DB::raw("employees.id,employees.biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) as empname,weekly_tmp_locations.loc_id,location_name"))
                            ->from('edtr')
                            ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                            ->join('payroll_period_weekly',function($join){
                                $join->whereRaw('dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
                            })
                            ->where('employees.emp_level',6)
                            ->where('exit_status',1)
                            ->where('payroll_period_weekly.id',$period_id)
                            ->leftJoin('weekly_tmp_locations',function($join) use ($period_id){
                                $join->on('weekly_tmp_locations.biometric_id','=','employees.biometric_id');
                                $join->where('weekly_tmp_locations.period_id','=',$period_id);
                            })
                            ->leftJoin('locations','locations.id','=','weekly_tmp_locations.loc_id')
                            ->distinct()
                            ->orderBy('empname','ASC');
        }

        

        //return $empWithPunch->get();
        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
             
				if($f['field'] == 'location_name'){
                    $result->where('location_name','like','%'.$f['value'].'%');
                }
                //$result->where($f['field'],'like','%'.$f['value'].'%');
                // if($f['field']=='empname'){
                //     $result->where('lastname','like','%'.$f['value'].'%')
                //     ->orWhere('firstname','like','%'.$f['value'].'%');
                // }

                if($f['field']=='empname'){
                    $result->where(function($query) use ($f) {
                        $query->where('lastname','like','%'.$f['value'].'%')
                            ->orWhere('firstname','like','%'.$f['value'].'%');
                    });
                }

                if($f['field']=='dept_name'){
                    $result->where(function($query) use ($f) {
                        $query->where('dept_name','like','%'.$f['value'].'%');
                    });
                }
			}
		}

		$total = $result->count(DB::raw('employees.id'));

		$result->limit($filter['pageSize'])->skip($filter['skip']);

		return [
			'total' => $total,
			'data' => $result->get()
		];

    }

    public function empWithDTRConfi($period_id,$filter,$type)
    {

        $result = $this->model->select(DB::raw("employees.id,employees.biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) as empname,concat(div_code,' - ',dept_name) as dept_name,div_name"))
                        ->from('edtr')
                        ->join('employees','edtr.biometric_id','=','employees.biometric_id')
                        ->join('payroll_period',function($join){
                            $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
                        })
                        ->join('departments','employees.dept_id','=','departments.id')
                        ->join('divisions','employees.division_id','=','divisions.id')
                        // ->whereIn('employees.emp_level',[1,2])
                        ->where('employees.emp_level','<',5)
                        ->where('exit_status',1)
                        ->where('payroll_period.id',$period_id)
                        ->distinct()
                        ->orderBy('empname','ASC');

        //return $empWithPunch->get();
        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
             
				if($f['field'] == 'location_name'){
                    $result->where('location_name','like','%'.$f['value'].'%');
                }
                //$result->where($f['field'],'like','%'.$f['value'].'%');
                // if($f['field']=='empname'){
                //     $result->where('lastname','like','%'.$f['value'].'%')
                //     ->orWhere('firstname','like','%'.$f['value'].'%');
                // }

                if($f['field']=='empname'){
                    $result->where(function($query) use ($f) {
                        $query->where('lastname','like','%'.$f['value'].'%')
                            ->orWhere('firstname','like','%'.$f['value'].'%');
                    });
                }

                if($f['field']=='dept_name'){
                    $result->where(function($query) use ($f) {
                        $query->where('dept_name','like','%'.$f['value'].'%');
                    });
                }
			}
		}

		$total = $result->count(DB::raw('employees.id'));

		$result->limit($filter['pageSize'])->skip($filter['skip']);

		return [
			'total' => $total,
			'data' => $result->get()
		];

    }

    public function getRawLogs($biometric_id,$period_id,$type)
    {
        if($type=='semi'){
            $result = $this->model->select('biometric_id','punch_date','punch_time','cstate','src')
                    ->from('edtr_raw')
                    ->where('biometric_id',$biometric_id)
                    ->join('payroll_period',function($join){
                        // $join->whereRaw('punch_date between payroll_period.date_from and payroll_period.date_to');
                        $join->whereRaw('punch_date between payroll_period.date_from and DATE_ADD(payroll_period.date_to,INTERVAL 1 DAY)');
                    })
                    ->where('payroll_period.id',$period_id)
                    ->orderBy('punch_date')
                    ->orderBy('punch_time');
        }else{
           // $range = $this->model->select('date_from','date_to')->from('payroll_period_weekly')->where('id',$period_id)->first();
          
            $result = $this->model->select('biometric_id','punch_date','punch_time','cstate','src')
                    ->from('edtr_raw')
                   
                    ->join('payroll_period_weekly',function($join){
                         $join->whereRaw('punch_date between payroll_period_weekly.date_from and DATE_ADD(payroll_period_weekly.date_to,INTERVAL 1 DAY)');
                        //$join->on(DB::raw('punch_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to'));
                    })
                    ->where('biometric_id',$biometric_id)
                    ->where('payroll_period_weekly.id',$period_id)
                    ->orderBy('punch_date')
                    ->orderBy('punch_time');
        }
        
        //WHERE biometric_id = 100 ORDER BY punch_date,punch_time;

        return $result->get();
    }

    public function getweeklyDTR($biometric_id,$period_id)
    {
        $holidays = $this->model->select(DB::raw("holidays.*,location_id"))
        ->from('holidays')
        ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
        ->join('payroll_period',function($join){
            $join->whereRaw('holiday_date between payroll_period.date_from and payroll_period.date_to');
        })
        ->where('payroll_period.id',$period_id);

        // $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,restday_hrs,restday_ot,sphol_hrs,sphol_ot,reghol_hrs,reghol_ot,reghol_pay,
        // case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type"))
        /*
        $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,CONCAT(lastname,', ',firstname) as empname,DATE_FORMAT(dtr_date,'%a') AS day_name,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) as work_sched,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,night_diff_ot,ifnull(schedule_id,0) schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,ot_in,ot_out,restday_hrs,restday_ot,restday_nd,restday_ndot,reghol_pay,reghol_hrs,reghol_ot,reghol_rd,reghol_rdnd,reghol_nd,reghol_ndot,sphol_pay,sphol_hrs,sphol_ot,sphol_rd,sphol_rdnd,sphol_nd,sphol_ndot,dblhol_pay,dblhol_hrs,dblhol_ot,dblhol_rd,dblhol_rdnd,dblhol_nd,dblhol_ndot,dblhol_rdot,sphol_rdot,reghol_rdot,reghol_rdndot,sphol_rdndot,dblhol_rdndot"))
                            ->from('edtr')
        ->from('edtr')
        ->where('edtr.biometric_id',$biometric_id)
        ->join('payroll_period_weekly',function($join){
            $join->whereRaw('dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
        })
        ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')
        ->leftJoinSub($holidays,'holidays',function($join) { //use ($type)
                            $join->on('holidays.location_id','=','employees.location_id');
                            $join->on('holidays.holiday_date','=','edtr.dtr_date');
                        })
        ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
        ->where('payroll_period_weekly.id',$period_id)
        ->orderBy('dtr_date');
        */
        $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,night_diff_ot,ifnull(schedule_id,0) schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,ot_in,ot_out,restday_hrs,restday_ot,restday_nd,restday_ndot,reghol_pay,reghol_hrs,reghol_ot,reghol_rd,reghol_rdnd,reghol_nd,reghol_ndot,sphol_pay,sphol_hrs,sphol_ot,sphol_rd,sphol_rdnd,sphol_nd,sphol_ndot,dblhol_pay,dblhol_hrs,dblhol_ot,dblhol_rd,dblhol_rdnd,dblhol_nd,dblhol_ndot,dblhol_rdot,sphol_rdot,reghol_rdot,reghol_rdndot,sphol_rdndot,dblhol_rdndot,cont"))
        ->from('edtr')
        ->where('edtr.biometric_id',$biometric_id)
        ->join('payroll_period_weekly',function($join){
            $join->whereRaw('dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
        })
        ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')

        ->leftJoinSub($holidays,'holidays',function($join) { //use ($type)
            $join->on('holidays.location_id','=','employees.location_id');
            $join->on('holidays.holiday_date','=','edtr.dtr_date');
        })
        ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
        ->where('payroll_period_weekly.id',$period_id)
        ->orderBy('dtr_date');

        return $result->get();
    }

    public function getSemiDTR_old($biometric_id,$period_id)
    {

        $holidays = $this->model->select(DB::raw("holidays.*,location_id"))
                    ->from('holidays')
                    ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                    ->join('payroll_period',function($join){
                        $join->whereRaw('holiday_date between payroll_period.date_from and payroll_period.date_to');
                    })
                    ->where('payroll_period.id',$period_id);


                    $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,ifnull(schedule_id,0) schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,lh_ot,lhot_rd,sh_ot,shot_rd,sun_ot,ot_in,ot_out"))
                            ->from('edtr')
                            ->where('edtr.biometric_id',$biometric_id)
                            ->join('payroll_period',function($join){
                            $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
                            })
                            ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')

                            ->leftJoinSub($holidays,'holidays',function($join) { //use ($type)
                            $join->on('holidays.location_id','=','employees.location_id');
                            $join->on('holidays.holiday_date','=','edtr.dtr_date');
                            })
                            ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
                            ->where('payroll_period.id',$period_id)
                            ->orderBy('dtr_date');

        /*
        $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,ifnull(schedule_id,0) schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,lh_ot,lhot_rd,sh_ot,shot_rd"))
        ->from('edtr')
        ->where('edtr.biometric_id',$biometric_id)
        ->join('payroll_period',function($join){
            $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
        })
        ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')
        ->leftJoin('holidays','edtr.dtr_date','=','holidays.holiday_date')
        ->join('holiday_location',function($join){
            $join->on('holiday_location.holiday_id','=','holiday_id');
            $join->on('holiday_location.location_id','=','employees.location_id');
        })
        ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
        ->where('payroll_period.id',$period_id)
        ->orderBy('dtr_date');
        */

        return $result->get();
    }

    public function getSemiDTR($biometric_id,$period_id)
    {

        $holidays = $this->model->select(DB::raw("holidays.*,location_id"))
                    ->from('holidays')
                    ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                    ->join('payroll_period',function($join){
                        $join->whereRaw('holiday_date between payroll_period.date_from and payroll_period.date_to');
                    })
                    ->where('payroll_period.id',$period_id);

                    $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,night_diff_ot,ifnull(schedule_id,0) schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,ot_in,ot_out,restday_hrs,restday_ot,restday_nd,restday_ndot,reghol_pay,reghol_hrs,reghol_ot,reghol_rd,reghol_rdnd,reghol_nd,reghol_ndot,sphol_pay,sphol_hrs,sphol_ot,sphol_rd,sphol_rdnd,sphol_nd,sphol_ndot,dblhol_pay,dblhol_hrs,dblhol_ot,dblhol_rd,dblhol_rdnd,dblhol_nd,dblhol_ndot,dblhol_rdot,sphol_rdot,reghol_rdot,reghol_rdndot,sphol_rdndot,dblhol_rdndot,awol"))
                            ->from('edtr')
                            ->where('edtr.biometric_id',$biometric_id)
                            ->join('payroll_period',function($join){
                                $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
                            })
                            ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')

                            ->leftJoinSub($holidays,'holidays',function($join) { //use ($type)
                                $join->on('holidays.location_id','=','employees.location_id');
                                $join->on('holidays.holiday_date','=','edtr.dtr_date');
                            })
                            ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
                            ->where('payroll_period.id',$period_id)
                            ->orderBy('dtr_date');

        return $result->get();
    }

    public function getSemiDTRsTTS($biometric_id,$period_id)
    {

        $holidays = $this->model->select(DB::raw("holidays.*,location_id"))
                    ->from('holidays')
                    ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                    ->join('payroll_period',function($join){
                        $join->whereRaw('holiday_date between payroll_period.date_from and payroll_period.date_to');
                    })
                    ->where('payroll_period.id',$period_id);

                    $result = $this->model->select(DB::raw("TIME_TO_SEC(work_schedules.time_in) sched_in_sec,TIME_TO_SEC(work_schedules.time_out) sched_out_sec,TIME_TO_SEC(edtr.time_in) as seconds_in,TIME_TO_SEC(edtr.time_out) as seconds_out,edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,night_diff_ot,ifnull(schedule_id,0) schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,ot_in,ot_out,restday_hrs,restday_ot,restday_nd,restday_ndot,reghol_pay,reghol_hrs,reghol_ot,reghol_rd,reghol_rdnd,reghol_nd,reghol_ndot,sphol_pay,sphol_hrs,sphol_ot,sphol_rd,sphol_rdnd,sphol_nd,sphol_ndot,dblhol_pay,dblhol_hrs,dblhol_ot,dblhol_rd,dblhol_rdnd,dblhol_nd,dblhol_ndot,dblhol_rdot,sphol_rdot,reghol_rdot,reghol_rdndot,sphol_rdndot,dblhol_rdndot"))
                            ->from('edtr')
                            ->where('edtr.biometric_id',$biometric_id)
                            ->join('payroll_period',function($join){
                                $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
                            })
                            ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')

                            ->leftJoinSub($holidays,'holidays',function($join) { //use ($type)
                                $join->on('holidays.location_id','=','employees.location_id');
                                $join->on('holidays.holiday_date','=','edtr.dtr_date');
                            })
                            // ->where([
                            //     ['edtr.biometric_id',5],
                            //     ['dtr_date','2023-01-04']
                            // ])
                            ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
                            ->where('payroll_period.id',$period_id)
                            ->orderBy('dtr_date');

        return $result->get();
    }

    public function getSemiDTRexp($period_id)
    {

        $holidays = $this->model->select(DB::raw("holidays.*,location_id"))
                    ->from('holidays')
                    ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                    ->join('payroll_period',function($join){
                        $join->whereRaw('holiday_date between payroll_period.date_from and payroll_period.date_to');
                    })
                    ->where('payroll_period.id',$period_id);

                    $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,CONCAT(lastname,', ',firstname) as empname,DATE_FORMAT(dtr_date,'%a') AS day_name,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) as work_sched,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,night_diff_ot,ifnull(schedule_id,0) schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,ot_in,ot_out,restday_hrs,restday_ot,restday_nd,restday_ndot,reghol_pay,reghol_hrs,reghol_ot,reghol_rd,reghol_rdnd,reghol_nd,reghol_ndot,sphol_pay,sphol_hrs,sphol_ot,sphol_rd,sphol_rdnd,sphol_nd,sphol_ndot,dblhol_pay,dblhol_hrs,dblhol_ot,dblhol_rd,dblhol_rdnd,dblhol_nd,dblhol_ndot,dblhol_rdot,sphol_rdot,reghol_rdot,reghol_rdndot,sphol_rdndot,dblhol_rdndot"))
                            ->from('edtr')
                            //->where('edtr.biometric_id',$biometric_id)
                            ->join('payroll_period',function($join){
                                $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
                            })
                            ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')

                            ->leftJoinSub($holidays,'holidays',function($join) { //use ($type)
                                $join->on('holidays.location_id','=','employees.location_id');
                                $join->on('holidays.holiday_date','=','edtr.dtr_date');
                            })
                            ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
                            ->where('payroll_period.id',$period_id)
                            ->orderBy('lastname')
                            ->orderBy('firstname')            
                            ->orderBy('dtr_date');

        return $result->get();
    }

    public function getSchedules()
    {
        //SELECT CONCAT(time_in,'-',time_out) AS schedule_desc FROM work_schedules
        $result = $this->model->select(DB::raw("id as schedule_id,CONCAT(time_in,'-',time_out) AS schedule_desc"))
                    ->from('work_schedules')
                    ->orderBy('time_in');

        return $result->get();
    }

    public function getSchedulesSat()
    {
        //SELECT CONCAT(time_in,'-',time_out) AS schedule_desc FROM work_schedules
        $result = $this->model->select(DB::raw("id as schedule_sat,CONCAT(time_in,'-',time_out) AS schedule_desc"))
                    ->from('work_schedules')
                    ->orderBy('time_in');

        return $result->get();
    }

    public function putLeavesUT($biometric_id,$period_id)
    {
       $result = $this->model->select()->from('filed_leaves_vw')->leftJoin('payroll_period',function($join){
            // $join->whereBetween('leave_date',['payroll_period.date_from','payroll_period.date_to']);
                $join->whereRaw('leave_date between payroll_period.date_from and payroll_period.date_to');
        })
        ->where([
            ['biometric_id',$biometric_id],
            ['payroll_period.id',$period_id]
        ])->get();

        foreach($result as $leave){
            /*  VL -
                SL -
                UT
                EL
                MP
                BL
                O
                */
                $ut = 0;
                $vl_wp = 0;
                $vl_wop = 0;
                $sl_wp = 0;
                $sl_wop = 0;
                $bl = 0;

            switch($leave->leave_type){
                case 'VL' :
                    $vl_wp = $leave->with_pay;
                    $vl_wop = $leave->without_pay;
                    break;
                case 'SL' :
                    $sl_wp = $leave->with_pay;
                    $sl_wop = $leave->without_pay;
                    break;
                case 'UT' : case 'EL' :
                    $ut = $leave->without_pay;
                    break;

                case 'BL' :
                    $bl = $leave->without_pay;
                    break;
                
                default : 
                    $vl_wp = $leave->with_pay;
                    $vl_wop = $leave->without_pay;
                break;
            }

            $arr = [
                'under_time' => $ut,
                'vl_wp' => $vl_wp,
                'vl_wop' => $vl_wop,
                'sl_wp' => $sl_wp,
                'sl_wop' => $sl_wop,
                'bl' => $bl,
                //'ndays' => 1 - round($ut/8,2) - round($vl_wop/8,2) - round($vl_wp/8,2) - round($sl_wp/8,2) - round($sl_wop/8,2) - round($bl/8,2),
            ];

            DB::table('edtr')
              ->where('biometric_id', $leave->biometric_id)
              ->where('dtr_date', $leave->leave_date)
              ->update($arr);
        }
       

       /*SELECT filed_leaves_vw.* FROM filed_leaves_vw LEFT JOIN payroll_period ON leave_date BETWEEN date_from AND date_to 
WHERE biometric_id = 19 AND payroll_period.id = 1;




*/
    }

    public function getSemiDTRforComputation($biometric_id,$period_id)
    {
        $holidays = $this->model->select(DB::raw("holidays.*,location_id"))
                            ->from('holidays')
                            ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                            ->join('payroll_period',function($join){
                                $join->whereRaw('holiday_date between payroll_period.date_from and payroll_period.date_to');
                            })
                            ->where('payroll_period.id',$period_id);
                            
        $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,schedule_id,time_to_sec(work_schedules.time_in) as sched_in,time_to_sec(work_schedules.time_out) as sched_out,time_to_sec(work_schedules.out_am) as sched_outam,time_to_sec(work_schedules.in_pm) as sched_inpm,holidays.holiday_type,time_to_sec(edtr.time_in) as actual_in,under_time,vl_wp,vl_wop,sl_wp,sl_wop,bl"))
        ->from('edtr')
        ->where('edtr.biometric_id',$biometric_id)
        ->join('payroll_period',function($join){
            $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
        })
        ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')
        //->leftJoin('holidays','edtr.dtr_date','=','holidays.holiday_date')
        ->leftJoinSub($holidays,'holidays',function($join) { //use ($type)
						$join->on('holidays.location_id','=','employees.location_id');
						$join->on('holidays.holiday_date','=','edtr.dtr_date');
		})
        ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
        ->where('payroll_period.id',$period_id)
        ->orderBy('dtr_date');

        return $result->get();
    }

    public function getWeeklyDTRforComputation($biometric_id,$period_id)
    {
        $holidays = $this->model->select(DB::raw("holidays.*,location_id"))
                            ->from('holidays')
                            ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                            ->join('payroll_period_weekly',function($join){
                                $join->whereRaw('holiday_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
                            })
                            ->where('payroll_period_weekly.id',$period_id);
                            
        $result = $this->model->select(DB::raw("COALESCE(weekly_tmp_locations.loc_id,employees.location_id) as location,edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,schedule_id,time_to_sec(work_schedules.time_in) as sched_in,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,time_to_sec(edtr.time_in) as actual_in,time_to_sec(ot_in) as ot_in_s,time_to_sec(ot_out) as ot_out_s,reghol_hrs,reghol_ot,cont,time_to_sec(work_schedules.out_am) as sched_outam,time_to_sec(work_schedules.in_pm) as sched_inpm"))
        //$result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,CONCAT(lastname,', ',firstname) as empname,DATE_FORMAT(dtr_date,'%a') AS day_name,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) as work_sched,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,night_diff_ot,ifnull(schedule_id,0) schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,ot_in,ot_out,restday_hrs,restday_ot,restday_nd,restday_ndot,reghol_pay,reghol_hrs,reghol_ot,reghol_rd,reghol_rdnd,reghol_nd,reghol_ndot,sphol_pay,sphol_hrs,sphol_ot,sphol_rd,sphol_rdnd,sphol_nd,sphol_ndot,dblhol_pay,dblhol_hrs,dblhol_ot,dblhol_rd,dblhol_rdnd,dblhol_nd,dblhol_ndot,dblhol_rdot,sphol_rdot,reghol_rdot,reghol_rdndot,sphol_rdndot,dblhol_rdndot"))
                            ->from('edtr')
        ->from('edtr')
        ->where('edtr.biometric_id',$biometric_id)
        ->join('payroll_period_weekly',function($join){
            $join->whereRaw('dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
        })
       
        ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')
        ->join('weekly_tmp_locations',function($join) use ($period_id){
            $join->on('weekly_tmp_locations.biometric_id','=','employees.biometric_id');
            $join->whereRaw('weekly_tmp_locations.period_id = '.$period_id);
        })
        //->leftJoin('holidays','edtr.dtr_date','=','holidays.holiday_date')
        ->leftJoinSub($holidays,'holidays',function($join) { //use ($type)
						$join->on('holidays.location_id','=','employees.location_id');
						$join->on('holidays.holiday_date','=','edtr.dtr_date');
		})
        ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
        ->where('payroll_period_weekly.id',$period_id)
        ->orderBy('dtr_date');

        return $result->get();
    }


    public function getWeeklyHolidaysforComputation($period_id)
    {
        $holidays = $this->model->select(DB::raw("holidays.*,location_id"))
                            ->from('holidays')
                            ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                            ->join('payroll_period_weekly',function($join){
                                $join->whereRaw('holiday_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
                            })
                            ->where('payroll_period_weekly.id',$period_id);
        
                            
        $result = $this->model->select(DB::raw("COALESCE(weekly_tmp_locations.loc_id,employees.location_id) as location,edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,schedule_id,time_to_sec(work_schedules.time_in) as sched_in,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,time_to_sec(edtr.time_in) as actual_in,time_to_sec(ot_in) as ot_in_s,time_to_sec(ot_out) as ot_out_s,reghol_hrs,reghol_ot,cont"))
        //$result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,CONCAT(lastname,', ',firstname) as empname,DATE_FORMAT(dtr_date,'%a') AS day_name,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) as work_sched,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,night_diff_ot,ifnull(schedule_id,0) schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type,ot_in,ot_out,restday_hrs,restday_ot,restday_nd,restday_ndot,reghol_pay,reghol_hrs,reghol_ot,reghol_rd,reghol_rdnd,reghol_nd,reghol_ndot,sphol_pay,sphol_hrs,sphol_ot,sphol_rd,sphol_rdnd,sphol_nd,sphol_ndot,dblhol_pay,dblhol_hrs,dblhol_ot,dblhol_rd,dblhol_rdnd,dblhol_nd,dblhol_ndot,dblhol_rdot,sphol_rdot,reghol_rdot,reghol_rdndot,sphol_rdndot,dblhol_rdndot"))
        
        ->from('edtr')
        // ->where('edtr.biometric_id',$biometric_id)
        ->join('payroll_period_weekly',function($join){
            $join->whereRaw('dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
        })
       
        ->leftJoin('employees','employees.biometric_id','=','edtr.biometric_id')
        ->join('weekly_tmp_locations',function($join) use ($period_id){
            $join->on('weekly_tmp_locations.biometric_id','=','employees.biometric_id');
            $join->whereRaw('weekly_tmp_locations.period_id = '.$period_id);
        })
        // ->leftJoin('holidays','edtr.dtr_date','=','holidays.holiday_date')
        ->joinSub($holidays,'holidays',function($join) { //use ($type)
						$join->on('holidays.location_id','=','employees.location_id');
						$join->on('holidays.holiday_date','=','edtr.dtr_date');
		})
        ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
        ->where('payroll_period_weekly.id',$period_id)
        ->orderBy('dtr_date');

        return $result->get();
    }

    // public function mapRawLogs($rawlogs)
    // {
    //     foreach($rawlogs as $logs)
    //     {
     
    //         switch($logs->cstate){
    //             case 'C/In';
    //                 $this->model->where([
    //                         ['biometric_id',$logs->biometric_id],
    //                         ['dtr_date',$logs->punch_date],
    //                     ])->update(['time_in' => $logs->punch_time]);
    //             break;

    //             case 'C/Out';
    //                     $this->model->where([
    //                         ['biometric_id',$logs->biometric_id],
    //                         ['dtr_date',$logs->punch_date],
    //                     ])->update(['time_out' => $logs->punch_time]);
    //             break;
    //         }
    //     }
    // }

    public function mapRawLogs($rawlogs)
    {
        foreach($rawlogs as $logs)
        {
     
            switch($logs->cstate){
                case 'C/In';
                    $this->model->where([
                            ['biometric_id',$logs->biometric_id],
                            ['dtr_date',$logs->punch_date],
                        ])->update(['time_in' => $logs->punch_time]);
                break;

                case 'C/Out';
                        $this->model->where([
                            ['biometric_id',$logs->biometric_id],
                            ['dtr_date',$logs->punch_date],
                        ])->update(['time_out' => $logs->punch_time]);
                break;
            }
        }
    }

    public function mapRawLogs2($dtr_log)
    {
       
        foreach($dtr_log as $dtr){
            // if($dtr->schedule_id>=5 || $dtr->schedule_id == null || $dtr->schedule_id == 'null' || $dtr->schedule_id == ''  ){
            if($dtr->schedule_id>=5 ){
                $in =  $this->model->select()
                ->from('edtr_raw')
                ->where([
                    ['punch_date',$dtr->dtr_date],
                    ['biometric_id',$dtr->biometric_id],
                    ['cstate','C/In']
                ])
                ->orderBy('punch_time')
                ->first();
                
                if($in){
                    //->whereRaw("punch_time<'$nextIn->punch_time'")

                    $out =  $this->model->select()
                    ->from('edtr_raw')
                    ->where([
                        ['punch_date',$dtr->dtr_date],
                        ['biometric_id',$dtr->biometric_id],
                        ['cstate','C/Out']
                    ])
                    ->whereRaw("punch_time>'$in->punch_time'")
                    ->orderBy('punch_time')
                    ->first();

                   
                }else {
                    $out =  $this->model->select()
                    ->from('edtr_raw')
                    ->where([
                        ['punch_date',$dtr->dtr_date],
                        ['biometric_id',$dtr->biometric_id],
                        ['cstate','C/Out']
                    ])
                    //->whereBetween('punch_date',[$dtr->dtr_date,$nextDay->format('Y-m-d')])
                    ->orderBy('punch_time')
                    ->first();
                }
               

                if($out==null){
                  
                    $nextDay = Carbon::createFromFormat('Y-m-d',$dtr->dtr_date)->addDay(); //addDays(n days) // subDay // subDays(n days)

                    $nextIn =  $this->model->select()
                    ->from('edtr_raw')
                    ->where([
                        ['punch_date',$nextDay->format('Y-m-d')],
                        ['biometric_id',$dtr->biometric_id],
                        ['cstate','C/In']
                    ])
                    ->orderBy('punch_time')
                    ->first();

                    if($nextIn){
                        $out =  $this->model->select()
                                ->from('edtr_raw')
                                ->where([
                                    ['punch_date',$nextDay->format('Y-m-d')],
                                    ['biometric_id',$dtr->biometric_id],
                                    ['cstate','C/Out']
                                ])
                                ->whereRaw("punch_time<'$nextIn->punch_time'")
                                //->whereBetween('punch_date',[$dtr->dtr_date,$nextDay->format('Y-m-d')])
                                ->first();
                    }
                    if($out==null){
                        $out =  $this->model->select()
                        ->from('edtr_raw')
                        ->where([
                            ['punch_date',$nextDay->format('Y-m-d')],
                            ['biometric_id',$dtr->biometric_id],
                            ['cstate','C/Out']
                        ])
                        //->whereRaw("punch_time<'$nextIn->punch_time'")
                        //->whereBetween('punch_date',[$dtr->dtr_date,$nextDay->format('Y-m-d')])
                        ->first();
                    }

                }
                
            }else{
                /**/
                $in =  $this->model->select()
                ->from('edtr_raw')
                ->where([
                    ['punch_date',$dtr->dtr_date],
                    ['biometric_id',$dtr->biometric_id],
                    ['cstate','C/In']
                ])
                ->orderBy('punch_time')
                ->first();
                if($in){
                    $out =  $this->model->select()
                        ->from('edtr_raw')
                        ->where([
                            ['punch_date',$dtr->dtr_date],
                            ['biometric_id',$dtr->biometric_id],
                            ['cstate','C/Out']
                        ])
                        ->whereRaw("TIME_TO_SEC(punch_time) > TIME_TO_SEC('".$in->punch_time."')")
                        ->orderBy('punch_time')
                        ->first();
                }else{
                    $out =  $this->model->select()
                        ->from('edtr_raw')
                        ->where([
                            ['punch_date',$dtr->dtr_date],
                            ['biometric_id',$dtr->biometric_id],
                            ['cstate','C/Out']
                        ])
                        ->orderBy('punch_time','desc')
                        ->first();
                }
                
               

            }

            $ot_in = $this->model->select()->from('edtr_raw')
            ->where([
                ['punch_date',$dtr->dtr_date],
                ['biometric_id',$dtr->biometric_id],
                ['cstate','OT/In']
            ])
            ->orderBy('punch_time','desc')
            ->first();

            $ot_out = $this->model->select() ->from('edtr_raw')
            ->where([
                ['punch_date',$dtr->dtr_date],
                ['biometric_id',$dtr->biometric_id],
                ['cstate','OT/Out']
            ])
            ->orderBy('punch_time','desc')
            ->first();

            // $dtr->ot_in = ($ot_in) ? $ot_in->punch_time : null;    
            // $dtr->ot_out = ($ot_out) ? $ot_out->punch_time : null;  
            

            // $dtr->time_in = ($in) ? $in->punch_time : null;    
            // $dtr->time_out = ($out) ? $out->punch_time : null;  

            $dtr->ot_in = ($ot_in) ? $ot_in->punch_time : $dtr->ot_in;
            $dtr->ot_out = ($ot_out) ? $ot_out->punch_time : $dtr->ot_out;  
            

            $dtr->time_in = ($in) ? $in->punch_time : $dtr->time_in;    
            $dtr->time_out = ($out) ? $out->punch_time : $dtr->time_out;  

            $this->updateValid($dtr->toArray());
        }       
    }

    public function computeEmpLogs($dtr,$type)
    {
        if($type=='semi'){
            foreach($dtr as $row)
            {
                $line  = $this->alignDataMontoSat($row);

                dd($line);
            }
        }
    }

    public function computeLogs($dtr,$type)
    {
       
        
        if($type=='semi'){
            foreach($dtr as $rec)
            {
                // var_dump($rec->holiday_type);

                //dd($rec);
              
                if($rec->schedule_id!=0){
                    
                    if($rec->actual_in > $rec->sched_in && $rec->actual_in < $rec->sched_outam ){
                        if($rec->actual_in > $rec->sched_in && $rec->actual_in < $rec->sched_outam - 900){
                            $late = (int)($rec->actual_in - $rec->sched_in)/60;
                            $quart = 0;
                            if($late>0){
                                /*
                                $quart = 0;
                                $quart +=  ($late<=15) ? 1 : floor($late/15) ;//round($late/15,0);
                            
                                $nlate = $late - ($quart * 15);
                                // $nlate = round($late/60,2);
                                $quart += ($nlate%15 > 0) ? 1 : 0;
                                */
                            
                            }
                            $rec->late = $late;
                            // $rec->late_eq = $quart * 0.25;

                            $rec->late_eq  = round($late/60,2);

                        }else{
                            $rec->late = 0;
                            $rec->late_eq = 0;
                        }
                    }else {
                        if($rec->actual_in > $rec->sched_inpm && $rec->actual_in < $rec->sched_out ){
                            if($rec->actual_in > $rec->sched_inpm){
                                $late = (int)($rec->actual_in - $rec->sched_inpm)/60;
                                $quart = 0;
                                if($late>0){
                                    $quart = 0;
                                    $quart +=  ($late<=15) ? 1 : floor($late/15) ;//round($late/15,0);
                                
                                    $nlate = $late - ($quart * 15);
                                    $nlate = round($late/60,2);
                                    // $quart += ($nlate%15 > 0) ? 1 : 0;
                                
                                }
                                $rec->late = $late;
                                // $rec->late_eq = $quart * 0.25;

                                $rec->late_eq  = round($late/60,2);

                            }else{
                                $rec->late = 0;
                                $rec->late_eq = 0;
                            }
                        }else {
                            $rec->late = 0;
                            $rec->late_eq = 0;
                        }
                    }

                   
                       
                  

                    // if($rec->day_name!='Sun'){
                    //     //var_dump($rec->day_name);
                    //     $rec->ndays = ($rec->time_in!='' && $rec->time_out!='' && $rec->holiday_type==NULL) ? 1 : 0;
                    //     //var_dump($rec->ndays);
                    // }

                    // $this->updateValid($rec->toArray());
                }

                // if($rec->day_name!='Sun'){
                //     $rec->ndays = ($rec->time_in!='' && $rec->time_out!='' && $rec->holiday_type==NULL && $rec->time_in!='00:00' && $rec->time_out!='00:00') ? 1 : 0;

                //     'ndays' => 1 - round($ut/8,2) - round($vl_wop/8,2) - round($vl_wp/8,2) - round($sl_wp/8,2) - round($sl_wop/8,2) - round($bl/8,2),
                // }
                
                if($rec->time_in!='' && $rec->time_out!='' && $rec->holiday_type==NULL && $rec->time_in!='00:00' && $rec->time_out!='00:00')
                {
                    $rec->ndays = 1 - round($rec->under_time/8,2) - round($rec->vl_wp/8,2) - round($rec->vl_wop/8,2) - round($rec->sl_wp/8,2) - round($rec->sl_wop/8,2) - round($rec->bl/8,2);
                }else{  
                    $rec->ndays = 0;
                }

                $this->updateValid($rec->toArray());
            }   
        }else {
          
            foreach($dtr as $rec)
            {
               
                $rec->ndays = ($rec->time_in!='' && $rec->time_out!='' && $rec->holiday_type==NULL && $rec->time_in!='00:00' && $rec->time_out!='00:00') ? 1 : 0;
                if($rec->ndays==0 || $rec->ndays==''){
                    $rec->ndays = ($rec->time_in!='' && $rec->time_out!='' && $rec->time_in!='00:00' && $rec->time_out!='00:00') ? 1 : 0;
                }

                /* */
                if($rec->schedule_id!=0){
                    // if((int)$rec->actual_in > (int) $rec->sched_in)
                    // {
                    //     dd($rec);
                    // }
                    if($rec->actual_in > $rec->sched_in && $rec->actual_in < $rec->sched_outam ){
                       
                        if($rec->actual_in > $rec->sched_in){
                            $late = (int)($rec->actual_in - $rec->sched_in)/60;
                            $quart = 0;
                            if($late>0){
                                  
                                $quart = 0;
                                $rec->late = $late;
                                // $rec->late_eq = $quart * 0.25;
                                $rec->late_eq  = round($late/60,2);
                            }
                        }else{
                            $rec->late = 0;
                            $rec->late_eq = 0;
                        }
                    }else {
                        
                        if($rec->actual_in > $rec->sched_inpm && $rec->actual_in < $rec->sched_out ){
                            // dd($rec);
                            if($rec->actual_in > $rec->sched_inpm){
                                $late = (int)($rec->actual_in - $rec->sched_inpm)/60;
                                $quart = 0;
                                if($late>0){
                                    $rec->late = $late;
                                }
                                $rec->late = $late;
                                // $rec->late_eq = $quart * 0.25;
                                $rec->late_eq  = round($late/60,2);
                            }else{
                                $rec->late = 0;
                                $rec->late_eq = 0;
                            }
                        }else {
                            $rec->late = 0;
                            $rec->late_eq = 0;
                        }
                    }

                }
                /* */                
                
                if($rec->ot_in_s > 0 && $rec->ot_out_s > 0){
                    if($rec->ot_out_s >= $rec->ot_in_s){
                        $ot = ($rec->ot_out_s - $rec->ot_in_s) ;
                    }else{
                        $ot = (($rec->ot_out_s + 86400)  - $rec->ot_in_s) ;
                    }

                    if($ot>=3600){
                        $cot = ($ot - ($ot % 1800))/3600;
                    }else{
                        $cot = 0;
                    }

                    // $rec->over_time = $cot;
                } else {
                    // $rec->over_time = 0;
                }

                switch($rec->holiday_type)
                {   
                // work here
                 
                    case 'SH': 

                            $flag = $this->checkLastWorkingDay($rec->dtr_date,$rec->location,$rec->biometric_id);

                            // $rec->sphol_pay = ($rec->ndays==0 || $rec->ndays =='') ? 1 : 0;
                            $rec->sphol_nd = $rec->night_diff;
                            // $rec->sphol_ot = $rec->overt_time;
                            $rec->sphol_ndot = $rec->night_diff_ot;
                        break;
                    
                    case 'LH': 
                         
                            $flag = $this->checkLastWorkingDay($rec->dtr_date,$rec->location,$rec->biometric_id);

                            if(($rec->time_in != "" && $rec->time_in != "00:00") && ($rec->time_out !="" && $rec->time_out !="00:00") || $rec->ndays > 0)
                            {
                                // $rec->ndays = 1 - round($rec->under_time/8,2) - round($rec->vl_wp/8,2) - round($rec->vl_wop/8,2) - round($rec->sl_wp/8,2) - round($rec->sl_wop/8,2) - round($rec->bl/8,2);
                               $rec->reghol_hrs = $rec->ndays * 8;
                            }

                            if($rec->reghol_hrs > 0){
                                $rec->reghol_pay = 0;
                              
                            }else{
                                if($flag){
                                    $rec->reghol_pay = 1;
                                }else{
                                    $rec->reghol_pay = 0;
                                }
                            }

                           


                            /*
                            else{

                                if($flag){
                                    $rec->reghol_pay = 1;
                                    // $rec->ndays = 0;
                                }else{
                                    if($rec->reghol_pay!=0){
    
                                    }
                                    
                                }
                            }

                            if($rec->cont=='Y')
                            {
                                // $rec->ndays = 0;
                                $rec->reghol_hrs =  $rec->over_time;
                            }
                          
                            // $rec->reghol_pay = ($rec->ndays==0 || $rec->ndays =='') ? 1 : 0;
                            $rec->reghol_nd = $rec->night_diff;
                            // $rec->reghol_ot = ($rec->cont=='Y') ? 0 :  $rec->over_time;
                            $rec->reghol_ndot = $rec->night_diff_ot;
                            $rec->over_time =0;
                            $rec->night_diff_ot =0;
                            */

                           
                        break; 
                    
                    case 'DBL': 
                            // $rec->dblhol_pay =  ($rec->ndays==0 || $rec->ndays =='') ? 1 : 0;
                            $rec->dblhol_nd = $rec->night_diff;
                            $rec->dblhol_ot = $rec->overt_time;
                            $rec->dblhol_ndot = $rec->night_diff_ot;
                        break; 
                    
                } 

               

                if(in_array($rec->holiday_type,['SH','LH','DBL'])){
                    // $rec->ndays = 0;
                    $rec->night_diff = 0;
                    $rec->overt_time = 0;
                    $rec->night_diff_ot = 0;
                }
               
                $this->updateValid($rec->toArray());
            }
        }
    }

    public function computeWeeklyHoliday($dtr,$type)
    {
       
        if($type=='semi'){
            
        }else {
            
            //echo "im here";
            foreach($dtr as $rec)
            {
             
                
                //$rec->ndays = ($rec->time_in!='' && $rec->time_out!='' && $rec->holiday_type==NULL && $rec->time_in!='00:00' && $rec->time_out!='00:00') ? 1 : 0;
                if($rec->ndays==0 || $rec->ndays==''){
                    // $rec->ndays = ($rec->time_in!='' && $rec->time_out!='' && $rec->time_in!='00:00' && $rec->time_out!='00:00') ? 1 : 0;
                }
                
                // if($rec->ot_in_s > 0 && $rec->ot_out_s > 0){
                //     if($rec->ot_out_s >= $rec->ot_in_s){
                //         $ot = ($rec->ot_out_s - $rec->ot_in_s) ;
                //     }else{
                //         $ot = (($rec->ot_out_s + 86400)  - $rec->ot_in_s) ;
                //     }

                //     if($ot>=3600){
                //         $cot = ($ot - ($ot % 1800))/3600;
                //     }else{
                //         $cot = 0;
                //     }

                //     $rec->over_time = $cot;
                // } else {
                //     $rec->over_time = 0;
                // }

                switch($rec->holiday_type)
                {   
                // work here
                 
                    case 'SH': 

                            $flag = $this->checkLastWorkingDay($rec->dtr_date,$rec->location,$rec->biometric_id);
                            // $rec->sphol_pay = ($rec->ndays==0 || $rec->ndays =='') ? 1 : 0;

                            if($flag){
                                $rec->sphol_pay = 0;
                                // $rec->sphol_pay = 1;
                                // $rec->ndays = 0;
                            }else{
                                // if($rec->reghol_pay!=0){

                                // }
                                $rec->sphol_pay = 0;
                                
                            }
                          
                        break;
                    
                    case 'LH': 
                          
                            $flag = $this->checkLastWorkingDay($rec->dtr_date,$rec->location,$rec->biometric_id);



                            if(($rec->time_in != "" && $rec->time_in != "00:00") && ($rec->time_out !="" && $rec->time_out !="00:00") || $rec->ndays >0)
                            {
                                // dd($rec,($rec->time_in != "" && $rec->time_in != "00:00") && ($rec->time_out !="" && $rec->time_out !="00:00") || (float) $rec->ndays >0);
                                $rec->reghol_pay = 1; // from 0
                                // $rec->ndays = 1;
                            }else{

                                
                                if($flag){
                                    $rec->reghol_pay = 1;
                                    // $rec->ndays = 0;
                                }else{
                                    // if($rec->reghol_pay!=0){
    
                                    // }
                                    $rec->reghol_pay = 0;
                                    
                                }
                            }

                            if($rec->ndays > 0)
                            {
                                $rec->reghol_hrs = $rec->ndays * 8;
                            }

                            // if($rec->biometric_id == 58)
                            // {
                            //     dd($rec,$flag);
                            // }

                            // if($rec->cont=='Y')
                            // {
                            //     $rec->ndays = 0;
                            //     $rec->reghol_hrs =  $rec->over_time;
                            // }
                          
                            // $rec->reghol_pay = ($rec->ndays==0 || $rec->ndays =='') ? 1 : 0;
                            // $rec->reghol_nd = $rec->night_diff;
                            // $rec->reghol_ot = ($rec->cont=='Y') ? 0 :  $rec->over_time;
                            // $rec->reghol_ndot = $rec->night_diff_ot;
                            // $rec->over_time =0;
                            // $rec->night_diff_ot =0;
                        break; 
                    
                    case 'DBL': 
                            // $rec->dblhol_pay =  ($rec->ndays==0 || $rec->ndays =='') ? 1 : 0;
                            // $rec->dblhol_nd = $rec->night_diff;
                            // $rec->dblhol_ot = $rec->overt_time;
                            // $rec->dblhol_ndot = $rec->night_diff_ot;

                            $flag = $this->checkLastWorkingDay($rec->dtr_date,$rec->location,$rec->biometric_id);

                            if(($rec->time_in != "" && $rec->time_in != "00:00") && ($rec->time_out !="" && $rec->time_out !="00:00") || $rec->ndays >0)
                            {
                                $rec->dblhol_pay = 1; // from 0
                                // $rec->ndays = 1;
                            }else{


                                if($flag){
                                    $rec->dblhol_pay = 1;
                                    // $rec->ndays = 0;
                                }else{
                                    // if($rec->dblhol_pay!=0){
    
                                    // }
                                    $rec->dblhol_pay = 0;
                                    
                                }
                            }
                        break; 
                    
                } 

                $this->updateValid($rec->toArray());
            }
        }
    }

    function checkLastWorkingDay($holiday,$location,$biometric_id)
    {
        $flag = true;
        $ctr = 1;

        $isEntitled = false;

        $holiday = Carbon::createFromFormat("Y-m-d",$holiday);

        do {
            $holiday->subDay();

            if($holiday->format('Y-m-d') != '2025-12-23' )
            {
                if(($holiday->format('D')!='Sun' && $biometric_id != 85) || (!in_array($holiday->format('D'), ['Sun','Sat']) && $biometric_id == 85) ){
                    /*  
                        SELECT holidays.id 
                        FROM holidays INNER JOIN holiday_location ON holidays.id = holiday_location.holiday_id
                        WHERE holidays.holiday_date = '' AND holiday_location.location_id = ''

                        check if its holiday
                    */
                $date = DB::table('holidays')
                            ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                            ->select()
                            ->where('holidays.holiday_date','=',$holiday->format('Y-m-d'))
                            ->where('holiday_location.location_id','=',$location);
    
                $worked = DB::table('edtr')->select('ndays')
                    ->where('biometric_id','=',$biometric_id)
                    ->where('dtr_date','=',$holiday->format('Y-m-d'))
                    ->first();
                
               
        
                if($date->count()<1){ // means it is not a holiday
                    $flag = false;
                    if($worked->ndays>0){
                        $isEntitled = true;
                    }
                } else {
        
                        if($worked->ndays>0){
                            $isEntitled = true;
    
                        }
                }

            }
            
            if($flag){
                $ctr++;
                if($ctr>=7){
                    $flag = false;
                }
            }
            }

            
           
        }while($flag);

        return $isEntitled;
    }

    public function getEmployeeForPrint($period_id,$type)
    {
        if($type=='semi')
        {
            $result = $this->model->select(DB::raw("employees.id,employees.biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) as empname"))
            ->from('edtr')
            ->join('employees','edtr.biometric_id','=','employees.biometric_id')
            ->join('payroll_period',function($join){
                $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
            })
            ->whereIn('pay_type',[1,2])
            ->where('exit_status',1)
            ->where('payroll_period.id',$period_id)
            ->distinct();
        }else{
            $result = $this->model->select(DB::raw("employees.id,employees.biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) as empname"))
            ->from('edtr')
            ->join('employees','edtr.biometric_id','=','employees.biometric_id')
            ->join('payroll_period_weekly',function($join){
                $join->whereRaw('dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
            })
            ->where('pay_type',3)
            ->where('exit_status',1)
            ->where('payroll_period_weekly.id',$period_id)
            ->distinct();
        }
        $employees = $result->get();
        foreach($employees as $employee){
            $dtr = $this->getSemiDTR($employee->biometric_id,$period_id);
            $employee->dtr = $dtr;
        }

        return $employees;

    }

    public function getEmployeeForiPrint($period_id,$biometric_id,$type)
    {
        if($type=='semi')
        {
            $result = $this->model->select(DB::raw("employees.id,employees.biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) as empname"))
            ->from('edtr')
            ->join('employees','edtr.biometric_id','=','employees.biometric_id')
            ->join('payroll_period',function($join){
                $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
            })
            // ->whereIn('pay_type',[1,2])
            ->where('employees.emp_level','<',6)
            ->where('exit_status',1)
            ->where('edtr.biometric_id',$biometric_id)
            ->where('payroll_period.id',$period_id)
            ->distinct();
        }else{
            $result = $this->model->select(DB::raw("employees.id,employees.biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) as empname"))
            ->from('edtr')
            ->join('employees','edtr.biometric_id','=','employees.biometric_id')
            ->join('payroll_period_weekly',function($join){
                $join->whereRaw('dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
            })
            ->where('employees.emp_level',6)
            ->where('exit_status',1)
            ->where('edtr.biometric_id',$biometric_id)
            ->where('payroll_period_weekly.id',$period_id)
            ->distinct();
        }
        $employees = $result->get();
        foreach($employees as $employee){
            $dtr = $this->getSemiDTR($employee->biometric_id,$period_id);
            $employee->dtr = $dtr;
        }

        return $employees;

    }

    function clearLogs($biometric_id,$period_id){
        DB::statement("UPDATE edtr INNER JOIN payroll_period ON dtr_date BETWEEN date_from AND date_to 
        SET time_in=NULL,time_out=NULL 
        WHERE biometric_id = $biometric_id AND payroll_period.id = $period_id");
    }

    public function onetimebigtime($period_id)
    {
        // $qry = " SELECT DISTINCT biometric_id FROM edtr_raw 
        // LEFT JOIN payroll_period ON edtr_raw.punch_date BETWEEN date_from AND date_to WHERE payroll_period.id = $period_id ";

        $qry = "SELECT DISTINCT employees.biometric_id FROM edtr_raw 
                inner join employees on employees.biometric_id = edtr_raw.biometric_id
                LEFT JOIN payroll_period ON edtr_raw.punch_date BETWEEN date_from AND date_to 
                WHERE payroll_period.id = $period_id
                and employees.emp_level <= 5";

        $result = DB::select($qry);

        return $result;
    }

    public function getBioIDinDTR($period_id)
    {
        $qry = " SELECT DISTINCT biometric_id FROM edtr
        LEFT JOIN payroll_period ON edtr.dtr_date BETWEEN date_from AND date_to WHERE payroll_period.id = $period_id ";
        $result = DB::select($qry);

        return $result;
    }

    public function mapSchedtoDTR($period_id)
    {
        $qry = "SELECT employees.biometric_id,schedule_id,dtr_date,sched_mtwtf,sched_sat,DATE_FORMAT(dtr_date,'%a') AS wday FROM edtr 
        INNER JOIN payroll_period ON edtr.dtr_date BETWEEN date_from AND date_to 
        INNER JOIN employees ON edtr.biometric_id = employees.biometric_id
        WHERE payroll_period.id = $period_id
        AND schedule_id = 0 AND DATE_FORMAT(dtr_date,'%a') != 'Sun'
        AND (sched_mtwtf IS NOT NULL OR sched_sat IS NOT NULL)
        ORDER BY employees.biometric_id,dtr_date;";

        $result = DB::select($qry);

        return $result;
    }

    public function alignDataMontoSat($line)
    {
        $tmp_timein = null;
        $tmp_timeout = null;

        // check in vs sched in
        /**** 
        if($line->sched_in_sec){
            if($line->seconds_in > $line->sched_in_sec){
                // late
            }else {
                $tmp_timein = $line->sched_in_sec;
            }
        }else {
            $tmp_timein = $line->seconds_in;
        }

        // check out vs sched out
       
        if($line->sched_out_sec)
        {
            if($line->seconds_out > $line->sched_out_sec){
                $tmp_timeout = $line->sched_out_sec;
            }else{
                // undertime
            }
        }
        else {
            $tmp_timeout = $line->seconds_out;
        }
        $hrs = ($tmp_timeout-$tmp_timein) / 3600;
        ****/
        $tmp_timein = $line->seconds_in;
        $tmp_timeout = $line->seconds_out;

        $time = array( //79200
            '82800',
            '86400',
            '3600',
            '7200',
            '10800',
            '14400',
            '18000',
            '21600',
        );
        /*
            82800 11
            86400 12
            3600 1
            7200 2
            10800 3
            14400 4
            18000 5
            21600 6
        */
        $nd = 0;
      
        if($tmp_timeout > $tmp_timein){  
            //if(($tmp_timein >= 79200 && $tmp_timein <= 86400) || ($tmp_timeout >= 82800 && $tmp_timeout <= 86400)){
            if($tmp_timeout >= 79200 && $tmp_timeout <= 86400){
                $night_nd = ($tmp_timeout - 79200);
            }
            else {
                $night_nd = 0;
               
            }

            $morning_nd =0;
        } else { /* timeout the next day*/
            $night_nd = 0 ; $morning_nd = 0;
            /* Night ND */
            if($tmp_timein<=79200){
                $night_nd = (86400 - 79200);
            }

            if($tmp_timein<=82800 && $tmp_timein > 79200){
                $night_nd = (86400 - 82800);
            }

            /* Check if timeout is greater than 6AM */
            if($tmp_timeout>=21600){
                $morning_nd = 21600;
            }else{
                $morning_nd = $tmp_timeout;
            }

            /* Morning ND */
            
            
        }

        $nd = ( (($night_nd + $morning_nd) - ($night_nd + $morning_nd) % 1800) /3600)  ;
        
        if($line->day_name =='Sun'){
            switch($line->holiday_type){
                case 'SH': 
                        $line->sphol_rdnd = $nd;
                    break;
                
                case 'LH': 
                        $line->reghol_rdnd = $nd;
                    break; 
                
                case 'DBL': 
                        $line->dblhol_rdnd = $nd;
                    break; 

                default : 
                        $line->restday_nd = $nd;
                    break;
            }
        }else{
            switch($line->holiday_type){
                case 'SH': 
                        $line->sphol_nd = $nd;
                    break;
                
                case 'LH': 
                        $line->reghol_nd = $nd;
                    break; 
                
                case 'DBL': 
                        $line->dblhol_nd = $nd;
                    break; 

                default : 
                        $line->night_diff = $nd;
                    break;
            }
        }
       
        return $line;
    }

    public function alignDataSun()
    {

    }

    public function getAllEmptoCompute($period_id)
    {
        $qry = "SELECT DISTINCT edtr.biometric_id FROM edtr INNER JOIN payroll_period_weekly ON dtr_date BETWEEN date_from AND date_to 
        INNER JOIN employees ON edtr.biometric_id = employees.biometric_id 
        WHERE payroll_period_weekly.id = $period_id AND employees.emp_level = 6";

        $result = DB::select($qry);

        return $result;
    }

    public function listDepartment()
    {
        $result = DB::table('departments')->select();
        // $result = DB::table('divisions')->select();

        return $result->get();
    }

    public function attendance_report($date_from,$date_to)
    {

        //SELECT biometric_id,COUNT(awol) AS awol FROM edtr WHERE dtr_date BETWEEN '2023-01-01' AND '2023-12-31' GROUP BY biometric_id;

        $awol = DB::table('edtr')->select(DB::raw("biometric_id,COUNT(awol) AS awol_count"))
                ->whereBetween('dtr_date',[$date_from,$date_to])
                ->where('awol','=','Y')
                ->groupBy('biometric_id');
        
        $vl = DB::table('edtr')->select(DB::raw("biometric_id,COUNT(id) AS vl_count"))
                ->whereBetween('dtr_date',[$date_from,$date_to])
                ->where(function($query){
                    $query->where('vl_wp', '>',0)
                    ->orWhere('vl_wop', '>', 0);
                })
                ->groupBy('biometric_id');
        
        $sl = DB::table('edtr')->select(DB::raw("biometric_id,COUNT(id) AS sl_count"))
                ->whereBetween('dtr_date',[$date_from,$date_to])
                ->where(function($query){
                    $query->where('sl_wp', '>',0)
                    ->orWhere('sl_wop', '>', 0);
                })
                ->groupBy('biometric_id');

        $ut = DB::table('edtr')->select(DB::raw("biometric_id,COUNT(id) AS ut_count"))
                ->whereBetween('dtr_date',[$date_from,$date_to])
                ->where('under_time','>',0)
                ->groupBy('biometric_id');
        
        $others = DB::table('edtr')->select(DB::raw("biometric_id,COUNT(id) AS others_count"))
                ->whereBetween('dtr_date',[$date_from,$date_to])
                ->where('other_leave','>=',0)
                ->groupBy('biometric_id');

        $tardy = DB::table('edtr')->select(DB::raw("biometric_id,COUNT(id) AS tardy_count"))
            ->whereBetween('dtr_date',[$date_from,$date_to])
            ->where('late','>',0)
            ->groupBy('biometric_id');

        $divisions = DB::table("divisions")->get();

        foreach($divisions as $div)
        {
            $departments = DB::table("departments")->where('dept_div_id','=',$div->id)->get();
                foreach($departments as $dept)
                {
                    $employees = $result = DB::table('employees')
                    ->join('employee_names_vw','employee_names_vw.biometric_id','=','employees.biometric_id')
                    ->select(DB::raw("employee_names_vw.*,ifnull(awol.awol_count,0) awol_count ,ifnull(vl.vl_count,0 ) vl_count,ifnull(sl.sl_count,0) sl_count,ifnull(ut_count,0) ut_count,ifnull(others_count,0) others_count,ifnull(tardy_count,0) tardy_count"))
                    
                    ->where('division_id','=',$div->id)
                    ->where('dept_id','=',$dept->id)

                    ->where('employees.exit_status','=',1)
                    ->where('employees.emp_level','<',6)
                    ->where('employees.date_hired','<',$date_to)
                    ->leftJoinSub($awol,'awol',function($join) { //use ($type)
                        $join->on('awol.biometric_id','=','employees.biometric_id');
                    })
                    ->leftJoinSub($vl,'vl',function($join) { //use ($type)
                        $join->on('vl.biometric_id','=','employees.biometric_id');
                    })
                    ->leftJoinSub($sl,'sl',function($join) { //use ($type)
                        $join->on('sl.biometric_id','=','employees.biometric_id');
                    })
                    ->leftJoinSub($ut,'ut',function($join) { //use ($type)
                        $join->on('ut.biometric_id','=','employees.biometric_id');
                    })
                    ->leftJoinSub($others,'others',function($join) { //use ($type)
                        $join->on('others.biometric_id','=','employees.biometric_id');
                    })
                    ->leftJoinSub($tardy,'tardy',function($join) { //use ($type)
                        $join->on('tardy.biometric_id','=','employees.biometric_id');
                    })
                    ->orderByRaw("(ifnull(awol_count,0) + ifnull(vl_count,0) + ifnull(sl_count,0) + ifnull(ut_count,0) + ifnull(others_count,0) + ifnull(tardy_count,0)) asc,lastname asc")
                    ->get();

                   

                    $dept->employees = $employees;
                   
                }

            $div->departments = $departments;
        }

        $result = $divisions;

        return $result;

         /*
        $result = DB::table('employees')
                            ->join('employee_names_vw','employee_names_vw.biometric_id','=','employees.biometric_id')
                            ->select(DB::raw("employee_names_vw.*,ifnull(awol.awol_count,0) awol_count ,ifnull(vl.vl_count,0 ) vl_count,ifnull(sl.sl_count,0) sl_count,ifnull(ut_count,0) ut_count,ifnull(others_count,0) others_count,ifnull(tardy_count,0) tardy_count"))
                            ->where('employees.exit_status','=',1)
                            ->where('employees.pay_type','<>',3)
                            ->where('employees.date_hired','<',$date_to)
                            ->leftJoinSub($awol,'awol',function($join) { //use ($type)
                                $join->on('awol.biometric_id','=','employees.biometric_id');
                            })
                            ->leftJoinSub($vl,'vl',function($join) { //use ($type)
                                $join->on('vl.biometric_id','=','employees.biometric_id');
                            })
                            ->leftJoinSub($sl,'sl',function($join) { //use ($type)
                                $join->on('sl.biometric_id','=','employees.biometric_id');
                            })
                            ->leftJoinSub($ut,'ut',function($join) { //use ($type)
                                $join->on('ut.biometric_id','=','employees.biometric_id');
                            })
                            ->leftJoinSub($others,'others',function($join) { //use ($type)
                                $join->on('others.biometric_id','=','employees.biometric_id');
                            })
                            ->leftJoinSub($tardy,'tardy',function($join) { //use ($type)
                                $join->on('tardy.biometric_id','=','employees.biometric_id');
                            })
                            ->orderByRaw("(ifnull(awol_count,0) + ifnull(vl_count,0) + ifnull(sl_count,0) + ifnull(ut_count,0) + ifnull(others_count,0) + ifnull(tardy_count,0)) asc,lastname asc");
            */ 
    }

    public function awol_setter($year,$month)
    {
        $start = $year.'-'.$month.'-01';
        $end = Carbon::createFromFormat('Y-m-d',$start)->format('Y-m-t');

        $employees = DB::table('employees')
        ->where('exit_status',1)
        ->where('pay_type','!=',3)
        ->where('employees.emp_level','!=',6)
        // ->where('biometric_id',1807)
        ->get();

        $logs = [];
        
        foreach($employees as $employee)
        {
            $e = new EmployeeAWOL($employee,$start,$end);

            array_push($logs,$e->getLogs());
        }

        return $logs;

    }

    // public function awol_setter($year,$month)
    // {
    //     $start = $year.'-'.$month.'-01';
    //     // $end = $year.'-'.$month.'-31';

    //     $end = Carbon::createFromFormat('Y-m-d',$start)->format('Y-m-t');

    //     // dd($year,$month,$end);

    //     $ctr = 0;

    //     DB::table('edtr')->whereBetween('dtr_date',[$start,$end])->update(['awol' => 'N']);

    //     $holidays_arr = [];

    //     $holidays = DB::table('holidays')->select('holiday_date')->whereBetween('holiday_date',[$start,$end])->get();

    //     foreach($holidays as $holiday)
    //     {
    //         array_push($holidays_arr,$holiday->holiday_date);
    //     }

    //     $result = DB::table('edtr')
    //         ->select(DB::raw("edtr.*,weekday(dtr_date) as day_num,division_id,dept_id"))
    //         ->join('employees','employees.biometric_id','=','edtr.biometric_id')
    //         ->where('employees.exit_status','=',1)
    //         ->where('employees.pay_type','<>',3)
    //         ->where('employees.date_hired','<',$end)
    //         ->whereRaw('WEEKDAY(dtr_date) not in (6)')
    //         ->whereBetween('dtr_date',[$start,$end])
    //         ->orderBy('id','ASC')
    //         ->chunk(1000,function(Collection $logs) use ($holidays_arr){
    //             foreach($logs as $log)
    //             {
    //                 $this->checkLog($log,$holidays_arr);
    //             }
    //         });

    // }

    public function checkLog($log,$holidays){

        $with_leave = false;
        $in_holiday = false;

        $awol_flag = 'Y';

        $no_saturday = [8,9,11,10];
        $no_saturday_emp = [129,428,167,69,1822,1812,695,840,907,39,895,889,41,1807,557,564,533];

        $leave = DB::table('leave_request_header')->join('leave_request_detail','leave_request_header.id','=','leave_request_detail.header_id')
        ->select('biometric_id','leave_type','with_pay','without_pay')
        ->where('document_status','=','POSTED')
        ->whereNotNull('received_by')
        ->where('is_canceled','=','N')
        ->where('leave_date','=',$log->dtr_date)
        ->where('leave_request_header.biometric_id','=',$log->biometric_id)
        ->first();

        // dd($leave);/

        if($leave)
        {
            switch($leave->leave_type)
            {
                case 'VL' : 
                        $log->vl_wp = $leave->with_pay;
                        $log->vl_wop = $leave->without_pay;

                    break;
                
                case 'SL' : 
                        $log->sl_wp = $leave->with_pay;
                        $log->sl_wop = $leave->without_pay;
                    break;

                case 'UT' : 
                        $log->under_time = $leave->with_pay + $leave->without_pay;
                    break;

                case 'MP' : 
                    $log->mlpl = 8;
                break;

                default : 
                        $log->other_leave = $leave->with_pay + $leave->without_pay;
                    break;
                
            }

            $with_leave = true;
        }

       
        $in_holiday = (in_array($log->dtr_date,$holidays));

        if(($log->time_in == null || $log->time_in == '00:00' || $log->time_in=="" ) && ($log->time_out == null || $log->time_out == '00:00' || $log->time_out==""))
        {
           // dd($holidays->toArray());
          
            if($in_holiday || $with_leave) {
                $awol_flag = 'N';
            }else{
                
                if($log->day_num == 5){
                    if(in_array($log->dept_id,$no_saturday) || in_array($log->biometric_id,$no_saturday_emp)){
                        $awol_flag = 'N';
                    }else {
                        $awol_flag = 'Y';
                    }
                }else {
                    $awol_flag = 'Y';
                }
            }
        }else {
            $awol_flag = 'N';
        }

        $log->awol = $awol_flag;



        $result = $this->updateValid((array) $log);

        if(!$result)
        {
            dd('oh no!');
        }
    }

    function downloadWeekly($period_id)
    {
        $tmp = DB::table('weekly_tmp_locations')->select('biometric_id','loc_id')->where('period_id','=',$period_id);

        // $result = DB::table('employees')->where('exit_status','=',1)->where('pay_type','=',3);
        $period = DB::table('payroll_period_weekly')->where('id','=',$period_id)->first();

        $locations = DB::table('locations')->get();                

        foreach($locations as $location)
        {
            $employees =  DB::table('employees')
                            ->leftJoinSub($tmp,'tmp',function($join){
                                $join->on('tmp.biometric_id','=','employees.biometric_id');
                            })  
                            ->join('employee_names_vw','employee_names_vw.biometric_id','=','employees.biometric_id')
                            ->select('employees.biometric_id','employee_names_vw.employee_name')
                            ->where('employees.exit_status','=',1)
                            ->where('employees.emp_level','=',6)
                            ->whereRaw("COALESCE(tmp.loc_id,employees.location_id) = ". $location->id)
                            ->get();

                foreach($employees as $employee)
                {
                    $raw = DB::table('edtr_raw')
                        ->select(DB::raw("biometric_id,punch_date,GROUP_CONCAT(punch_time ORDER BY punch_time ASC SEPARATOR ' ') AS cincout"))
                        ->whereBetween('punch_date',[$period->date_from,$period->date_to])
                        // ->where('punch_time','<>','00:00')
                        ->where('biometric_id',$employee->biometric_id)
                        ->groupBy('biometric_id')
                        ->groupBy('punch_date');
                    
                    $dtr = DB::table('edtr')->select(DB::raw("edtr.*,cincout"))
                            ->leftJoinSub($raw, 'rawdtr', function ($join) {
                                $join->on('rawdtr.biometric_id', '=', 'edtr.biometric_id');
                                $join->on('rawdtr.punch_date', '=', 'edtr.dtr_date');
                            })
                            ->whereBetween('edtr.dtr_date',[$period->date_from,$period->date_to])
                            ->where('edtr.biometric_id',$employee->biometric_id)
                            ->orderBy('dtr_date','ASC')
                            ->get();

                    $employee->dtr = $dtr;
                }

            $location->employees = $employees;
        }

        return $locations;

    }

    public function downloadDTRSemi($period_id)
    {
        $employees = DB::table('employees')
                    ->select(DB::raw("employees.id,CONCAT(lastname,', ', firstname) AS emp_name,emp_pay_types.pay_description,biometric_id,time_in,time_out"))
                    ->leftJoin('emp_pay_types','emp_pay_types.id','=','pay_type')
                    ->leftJoin('work_schedules','sched_mtwtf','=','work_schedules.id')
                    ->where('exit_status',1)
                    // ->where('pay_type','!=',3)
                    ->where('employees.emp_level','<',6)
                    ->where('job_title_id','!=',130)
                    ->get();
        //work_schedules ON sched_mtwtf = work_schedules.id
        foreach($employees as $employee){

            $dtr = DB::select(DB::raw("SELECT edtr.*,date_format(dtr_date,'%a') as day_name FROM edtr LEFT JOIN payroll_period ON dtr_date BETWEEN date_from AND date_to 
                WHERE biometric_id = $employee->biometric_id AND payroll_period.id = $period_id
                ORDER BY dtr_date ASC"));

            foreach($dtr as $row)
            {
                $punch = DB::table('edtr_raw')->select(DB::raw("GROUP_CONCAT(punch_time ORDER BY punch_time ASC SEPARATOR ' ') AS cincout"))
                    ->where('biometric_id', $employee->biometric_id)
                    ->where('punch_date',$row->dtr_date)
                    ->first();
                $row->punch = $punch;
            }

            $employee->dtr = $dtr;
        }

       

        return $employees;
    }

   

}

/*
SELECT GROUP_CONCAT(punch_time ORDER BY punch_time ASC SEPARATOR ' ') AS cincout FROM edtr_raw WHERE biometric_id = 847 AND punch_date = '2022-12-01'; 

left join emp_pay_types on emp_pay_types.id = pay_type;

SELECT biometric_id,COUNT(awol) AS awol FROM edtr WHERE dtr_date BETWEEN '2023-01-01' AND '2023-12-31' GROUP BY biometric_id;

 +"id": "674"
  +"biometric_id": "1"
  +"encoded_on": "2023-02-03 10:41:43"
  +"encoded_by": "1"
  +"request_date": "2023-02-03 10:41:43"
  +"leave_type": "VL"
  +"date_from": "2023-01-05"
  +"date_to": "2023-01-06"
  +"remarks": "celebrate 1st year anniversary with wife"
  +"acknowledge_status": "Approved"
  +"acknowledge_time": "2023-06-06 14:16:31"
  +"acknowledge_by": "1"
  +"received_by": "1"
  +"received_time": "2023-02-03 10:41:43"
  +"dept_id": null
  +"division_id": null
  +"job_title_id": null
  +"document_status": "POSTED"
  +"reliever_id": null
  +"ack_by_reliver": null
  +"deny_reason": null
  +"line_id": "889"
  +"header_id": "674"
  +"leave_date": "2023-01-05"
  +"is_canceled": "N"
  +"time_from": "07:45"
  +"time_to": "17:00"
  +"days": "1.00"
  +"with_pay": "8.00"
  +"without_pay": "0.00"
  +"leave_remarks": null

SELECT biometric_id,leave_type,with_pay + without_pay 
FROM leave_request_header INNER JOIN leave_request_detail ON leave_request_header.id = leave_request_detail.header_id
WHERE document_status = 'POSTED' AND received_by IS NOT NULL AND is_canceled = 'N' AND leave_date = '2023-01-01' AND leave_request_header.biometric_id = 847;


SELECT * FROM filed_leaves_vw INNER JOIN employees ON filed_leaves_vw.biometric_id = employees.biometric_id 
WHERE employees.exit_status = 1 AND employees.date_hired < 



SELECT DISTINCT edtr.biometric_id FROM edtr INNER JOIN payroll_period_weekly ON dtr_date BETWEEN date_from AND date_to 
INNER JOIN employees ON edtr.biometric_id = employees.biometric_id 
WHERE payroll_period_weekly.id = 1 AND employees.pay_type = 3



SELECT leave_date,with_pay,without_pay,leave_type FROM leave_request_header 
INNER JOIN leave_request_detail ON leave_request_detail.header_id = leave_request_header.id
INNER JOIN payroll_period ON leave_request_detail.leave_date BETWEEN payroll_period.date_from AND payroll_period.date_to
WHERE document_status = 'POSTED'
AND received_by IS NOT NULL
AND payroll_period.id = 2
AND leave_request_header.biometric_id = 808;


SELECT punch_time,cstate FROM edtr_raw 
WHERE punch_date = '2022-08-01'
AND biometric_id = 871
AND cstate = 'C/In'
ORDER BY punch_time LIMIT 1



SELECT punch_time,cstate FROM edtr_raw
LEFT JOIN edtr ON dtr_date = punch_date  
LEFT JOIN work_schedules ON edtr.schedule_id = work_schedules.id
WHERE punch_date = '2022-08-01'
AND edtr_raw.biometric_id = 871
AND TIME_TO_SEC(punch_time) < TIME_TO_SEC(work_schedules.time_in)
AND cstate = 'C/In'
ORDER BY punch_time LIMIT 1



   //    $dtr[$logs->punch_date][$logs->biometric_id] = [
        //         // 'biometric_id'=>null,
        //         // 'dtr_date'=>null,
        //         'time_in' =>null,
        //         'time_out' =>null, 
        //    ];
            // $dtr = $this->model->select()->where([
            //     ['biometric_id',$logs->biometric_id],
            //     ['dtr_date',$logs->punch_date],
            // ])
            // ->first();

            // dd($dtr);


 $empWithPunch = $this->model->select(DB::raw("employees.id,employees.biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) as empname"))->from('employees')
                        ->join('edtr_raw',function($join){

                        })
                        ->where('pay_type',3)
                        ->where('exit_status',1);


SELECT id,biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) as empname
FROM employees WHERE pay_type=3 AND exit_status = 1
ORDER BY lastname,firstname

	SELECT DISTINCT biometric_id 
FROM payroll_period 
INNER JOIN edtr_raw ON  edtr_raw.punch_date BETWEEN payroll_period.date_from AND payroll_period.date_to
WHERE payroll_period.id =1;


$result = $this->model->select('edtr_empid')
    						  ->from('hris_edtr as a')
    						  ->join('hris_payperiod as b',function($join){
    						  	$join->whereRaw('a.edtr_date between b.payperiod_start and b.payperiod_end');
    						  })
    						  ->where('b.payperiod_id','=',(int)$period_id)
                  ->groupBy('edtr_empid')
                  ->havingRaw('sum(edtr_hrs) > ?', [0])
    						  ->distinct();
*/