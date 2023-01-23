<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UploadLogMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\UploadLog';
    
    protected $rules = [
    	
    ];

    public function insertDB($logs)
    {
        $result = DB::table('edtr_raw')->insertOrIgnore($logs);
    }

    // public function insertDB_CSV($logs)
    // {
    //     $result = DB::table('edtr_raw')->insertOrIgnore($logs);
    // }

    public function updatefromCSV($key,$data)
    {
        DB::table('edtr')->where($key)->update($data);
    }

}
