<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveCreditMaker extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\LeaveCredits';
    protected $rules = [
      
    ];

    protected $messages = [
       
    ];

    public function process()
    {
        $result =DB::table('employees')->select(DB::raw("biometric_id,birthdate"))->whereNotNull('birthdate')->get();

        //dd($result);
    }

}