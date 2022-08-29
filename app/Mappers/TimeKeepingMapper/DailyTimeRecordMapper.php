<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

class DailyTimeRecordMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\DailyTimeRecord';
    protected $rules = [
        'biometric_id' => 'required|sometimes',
        'dtr_date' => 'required|sometimes',
    ];

    protected $messages = [
        
    ];

    public function prepDTRbyPeriod($period_id,$type)
    {
        $blank_dtr = [];

        if($type=='semi'){
            $empWithPunch = $this->model->select('edtr_raw.biometric_id')->from('edtr_raw')
            ->join('employees','edtr_raw.biometric_id','=','employees.biometric_id')
            ->join('payroll_period',function($join){
               $join->whereRaw('punch_date between payroll_period.date_from and payroll_period.date_to');
            })
            ->whereIn('pay_type',[1,2])
            ->where('exit_status',1)
            ->where('payroll_period.id',$period_id)
            ->distinct()
            ->get();
            $range = $this->model->select('date_from','date_to')->from('payroll_period')->where('payroll_period.id',$period_id)->first();
        }else{
            $empWithPunch = $this->model->select('edtr_raw.biometric_id')->from('edtr_raw')
            ->join('employees','edtr_raw.biometric_id','=','employees.biometric_id')
            ->join('payroll_period_weekly',function($join){
                //$join->whereBetween('punch_date',['payroll_period_weekly.date_from','payroll_period_weekly.date_to']);
                $join->whereRaw('punch_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
            })
            ->where('pay_type',3)
            ->where('exit_status',1)
            ->where('payroll_period_weekly.id',$period_id)
            ->distinct()
            ->get();
            $range = $this->model->select('date_from','date_to')->from('payroll_period_weekly')->where('payroll_period_weekly.id',$period_id)->first();

        }

       

       
        $period = CarbonPeriod::create($range['date_from'],$range['date_to']);

        foreach($empWithPunch as $emp)
        {
            foreach ($period as $date) {
                array_push($blank_dtr,['biometric_id' => $emp->biometric_id, 'dtr_date' => $date->format('Y-m-d')]);
            }
           
        }

        $result = DB::table('edtr')->insertOrIgnore($blank_dtr);


        return $result;
      
    }

    public function empWithDTR($period_id,$filter,$type)
    {

        if($type=='semi'){
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

        

        //return $empWithPunch->get();
        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count(DB::raw('employees.id'));

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('empname','ASC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

    }

    public function getRawLogs($biometric_id,$period_id,$type)
    {
        if($type=='semi'){
            $result = $this->model->select('biometric_id','punch_date','punch_time','cstate')
                    ->from('edtr_raw')
                    ->where('biometric_id',$biometric_id)
                    ->join('payroll_period',function($join){
                        $join->whereRaw('punch_date between payroll_period.date_from and payroll_period.date_to');
                    })
                    ->where('payroll_period.id',$period_id)
                    ->orderBy('punch_date')
                    ->orderBy('punch_time');
        }else{
            $result = $this->model->select('biometric_id','punch_date','punch_time','cstate')
                    ->from('edtr_raw')
                    ->where('biometric_id',$biometric_id)
                    ->join('payroll_period_weekly',function($join){
                        $join->whereRaw('punch_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
                    })
                    ->where('payroll_period_weekly.id',$period_id)
                    ->orderBy('punch_date')
                    ->orderBy('punch_time');
        }
        
       
        //WHERE biometric_id = 100 ORDER BY punch_date,punch_time;

        return $result->get();
    }

    public function getweeklyDTR($biometric_id,$period_id)
    {
        $result = $this->model->select(DB::raw("edtr.id,biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc"))
        ->from('edtr')
        ->where('biometric_id',$biometric_id)
        ->join('payroll_period_weekly',function($join){
            $join->whereRaw('dtr_date between payroll_period_weekly.date_from and payroll_period_weekly.date_to');
        })
        ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
        ->where('payroll_period_weekly.id',$period_id)
        ->orderBy('dtr_date');

        return $result->get();
    }

    public function getSemiDTR($biometric_id,$period_id)
    {
        $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,schedule_id,CONCAT(work_schedules.time_in,'-',work_schedules.time_out) AS schedule_desc,case when holiday_type=1 then 'LH' when holiday_type=2 then 'SH' when holiday_type=3 then 'DLH' else '' end as holiday_type"))
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

    public function getSemiDTRforComputation($biometric_id,$period_id)
    {
        $result = $this->model->select(DB::raw("edtr.id,edtr.biometric_id,DATE_FORMAT(dtr_date,'%a') AS day_name,dtr_date,edtr.time_in,edtr.time_out,late,late_eq,ndays,under_time,over_time,night_diff,schedule_id,time_to_sec(work_schedules.time_in) as sched_in,holiday_type,time_to_sec(edtr.time_in) as actual_in"))
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
            if($dtr->schedule_id>=5){
                $in =  $this->model->select()
                ->from('edtr_raw')
                ->where([
                    ['punch_date',$dtr->dtr_date],
                    ['biometric_id',$dtr->biometric_id],
                    ['cstate','C/In']
                ])
                ->orderBy('punch_time')
                ->first();

                $nextDay = Carbon::createFromFormat('Y-m-d',$dtr->dtr_date)->addDay(); //addDays(n days) // subDay // subDays(n days)

                $out =  $this->model->select()
                ->from('edtr_raw')
                ->where([
                    ['punch_date',$nextDay->format('Y-m-d')],
                    ['biometric_id',$dtr->biometric_id],
                    ['cstate','C/Out']
                ])
                ->orderBy('punch_time')
                ->first();
                
            }else{
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
                        ->orderBy('punch_time')
                        ->first();
                }
                
               

            }

            $dtr->time_in = ($in) ? $in->punch_time : null;    
            $dtr->time_out = ($out) ? $out->punch_time : null;  

            $this->updateValid($dtr->toArray());
        }       
    }

    public function computeLogs($dtr,$type)
    {
        if($type=='semi'){
            foreach($dtr as $rec)
            {
                // var_dump($rec->holiday_type);
               
                if($rec->schedule_id!=0){
                    
                    if($rec->actual_in > $rec->sched_in){
                        $late = ($rec->actual_in - $rec->sched_in)/60;

                        if($late>0){
                            $quart = 0;
                            $quart += round($late/15,0);
                            $quart += ($late%15 > 0) ? 1 : 0;
                        }

                        $rec->late = $late;
                        $rec->late_eq = $quart * 0.25;
                    }else{
                        $rec->late = 0;
                        $rec->late_eq = 0;
                    }

                    if($rec->day_name!='Sun'){
                        //var_dump($rec->day_name);
                        $rec->ndays = ($rec->time_in!='' && $rec->time_out!='' && $rec->holiday_type==NULL) ? 1 : 0;
                        //var_dump($rec->ndays);
                    }

                    $this->updateValid($rec->toArray());
                }
            }   
        }
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
        $employees = $result->limit(5)->get();
        foreach($employees as $employee){
            $dtr = $this->getSemiDTR($employee->biometric_id,$period_id);
            $employee->dtr = $dtr;
        }

        return $employees;

    }

   

}

/*
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