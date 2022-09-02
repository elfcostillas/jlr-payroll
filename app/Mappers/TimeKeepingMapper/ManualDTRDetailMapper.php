<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManualDTRDetailMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\ManualDTRDetail';
    protected $rules = [
        'biometric_id' => 'required|sometimes',
        'header_id'=> 'required|sometimes',
        'biometric_id'=> 'required|sometimes',
        'dtr_date'=> 'required|sometimes',
        //'time_in'=> 'required|sometimes',
        //'time_out'=> 'required|sometimes',
        //'overtime_in'=> 'required|sometimes',
        //'overtime_out'=> 'required|sometimes',
        //'overtime_hrs'=> 'required|sometimes',
        //'reg_hrs',
        //'reg_day',
        // 'rd_hrs',
        // 'rd_ot',
        // 'sh_hrs',
        // 'sh_ot',
        // 'lh_hrs',
        // 'lh_ot',
        //'remarks'=> 'required|sometimes',
        
    ];

    public function list($filter)
    {
       
    }

    public function insertBatch($arr)
    {
        $result = DB::table('manual_dtr_details')->insertOrIgnore($arr);
    }

    public function listDetail($header_id)
    {
        $result = $this->model->select(DB::raw("manual_dtr_details.*,DATE_FORMAT(dtr_date,'%a') as dayname"))->where('header_id',$header_id);

        return $result->get();
    }


}

