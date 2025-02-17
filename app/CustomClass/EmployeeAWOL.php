<?php

namespace App\CustomClass;

use Illuminate\Support\Facades\DB;

class EmployeeAWOL
{
    //

    private $info;
    private $start;
    private $end;

    private $logs = [];



    public function __construct($info,$start,$end)
    {
        $this->info = $info;
        $this->start = $start;
        $this->end = $end;

        $this->process();

       
    }

    public function process()
    {
        $holidays = DB::table('holidays')
            ->select(DB::raw("holidays.*,location_id"))
            ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
            ->whereBetween('holiday_date',[$this->start,$this->end]);

        $leaves = DB::table('leave_request_header')
                ->select()
                ->join('leave_request_detail','leave_request_header.id','=','leave_request_detail.header_id')
                ->where('biometric_id',$this->info->biometric_id)
                ->where('document_status','POSTED')
                ->where('is_canceled','N')
                ->whereBetween('leave_date',[$this->start,$this->end]);
     
        $dtr = DB::table('edtr')
            ->select(DB::raw("*,DAYNAME(dtr_date) as day_name,edtr.id as dtr_id"))
            ->join('employees','employees.biometric_id','=','edtr.biometric_id')
            ->leftJoinSub($holidays,'holidays',function($join){
                $join->on('holidays.location_id','=','employees.location_id');
                $join->on('holidays.holiday_date','=','edtr.dtr_date');
            })
            ->leftJoinSub($leaves,'leaves',function($join){
                $join->on('leaves.biometric_id','=','employees.biometric_id');
                $join->on('edtr.dtr_date','=','leaves.leave_date');
            })
            ->where('employees.biometric_id',$this->info->biometric_id)
            ->whereRaw("DAYNAME(dtr_date) != 'Sunday'")
            ->whereBetween('dtr_date',[$this->start,$this->end])
            ->get();

        foreach($dtr as $log)
        {
            if($log->day_name == 'Saturday'){
                // dd($log,$log->day_name);
            }else{
               
                if($this->isEmpty($log->time_in) && $this->isEmpty($log->time_out) && $this->isZero($log->ndays) && is_null($log->holiday_type) && is_null($log->leave_type))
                {
                    $this->setVerdict($log->dtr_id,'Y');
                }else{
                    $this->setVerdict($log->dtr_id,'N');
                }
            }
        }

        // return $dtr;
    }

    public function isZero($number)
    {
        return ($number == 0 || $number == '' || is_null($number) ) ? true : false;
    }

    public function isEmpty($clock_in)
    {
        return ($clock_in == '00:00' || $clock_in == '' || is_null($clock_in) ) ? true : false;
    }

    public function getLogs()
    { 
        return $this->logs;
    }

    public function setVerdict($dtr_id,$value)
    {
        DB::table('edtr')->where('id','=',$dtr_id)->update(['awol' => $value]);
    }
   
}
