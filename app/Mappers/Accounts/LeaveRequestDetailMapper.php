<?php

namespace App\Mappers\Accounts;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonPeriod;

class LeaveRequestDetailMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Accounts\LeaveRequestDetail';
    protected $rules = [
    	
    ];

    public function createDates($arr,$id)
    {
        $period = CarbonPeriod::create($arr['date_from'],$arr['date_to']);
       
        $leave_details = [];
        foreach($period as $date)
        {
            if($date->format('D')!='Sun'){
                array_push($leave_details,[
                    'header_id' => $id,
                    'leave_date' => $date,
                    'is_canceled' => 'N',
                    'time_from' => '00:00',
                    'time_to' => '00:00',
                    'days' => 1,
                    'with_pay' => 0,
                    'without_pay' => 0
                ]);
            }
           
        }

        $result = DB::table('leave_request_detail')->insertOrIgnore($leave_details);

    }

    public function listDates($id)
    {
        $result = $this->model->select(DB::raw("line_id,header_id,leave_date,is_canceled,time_from,time_to,days,with_pay,without_pay,DATE_FORMAT(leave_date,'%a') as dayname")
            
        )->where('header_id',$id);

        //DATE_FORMAT(leave_date,'%a')
        /*
        'line_id',
            'header_id',
            'leave_date',
            'is_canceled',
            'time_from',
            'time_to',
            'days',
            'with_pay',
            'without_pay'
            */

        return $result->get();
    }

    public function deleteDetail($id)
    {
        $result = $this->model->where('header_id',$id)->delete();
    }


    /*
line_id
header_id
leave_date
is_canceled
time_from
time_to
days
    */
    

    
}
