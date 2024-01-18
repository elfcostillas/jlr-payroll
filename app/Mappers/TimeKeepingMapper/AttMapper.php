<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

class AttMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\DailyTimeRecord';
    protected $rules = [
        'biometric_id' => 'required|sometimes',
        'dtr_date' => 'required|sometimes',
    ];

    public function download($from,$to)
    {
        $blanks = [];

        $db = [
            [
                'table' => 'ATTLogsNorth',
                'loc_id' => 1
            ],
            [
                'table' => 'ATTLogsQAD',
                'loc_id' => 3
            ],
            [
                'table' => 'ATTLogsSinoma',
                'loc_id' => 4
            ],
            [
                'table' => 'ATTLogsSouth',
                'loc_id' => 2
            ],
        ];

        foreach($db as $table)
        {
            // dd($table['table']);
            $logs = DB::connection('att')
                        ->table($table['table'])
                        ->whereBetween('DateOnly',[$from,$to])
                        ->get();
            
            foreach($logs as $log){
               
                $l = array(
                    'punch_date' => $log->DateOnly,
                    'punch_time' => substr($log->TimeOnly,0,5),
                    'biometric_id' => $log->BiometricID,
                    'cstate' => $log->Description,
                    'src' => 'bio',
                );

                array_push($blanks,$l);
            }
        }

        $result = DB::connection('mysql')->table('edtr_raw')->insertOrIgnore($blanks);

        return $result;
    }   

}

/*


  +"ID": "22785"
  +"MachineNumber": "0"
  +"BiometricID": "957"
  +"DateTimeBoth": "2024-01-01 06:44:09"
  +"DateOnly": "2024-01-01"
  +"TimeOnly": "06:44:09"
  +"State": "0"
  +"Description": "C/In"

*/