<?php

namespace App\Mappers\EmployeeFileMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OnlineRequestUserMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\EmployeeFile\OnlineFormRequests';
    protected $rules = [
    	//'dept_id' => 'required|sometimes|gt:0',
		//'job_title_code' => 'required|sometimes|min:2|max:6',
		//'job_title_name' => 'required|sometimes|min:4|max:32'
		//'job_title_code' => 'required|sometimes|unique:job_titles|min:2|max:6',
		//'job_title_name' => 'required|sometimes|unique:job_titles|min:4|max:32'
    ];

	protected $messages = [
		
	];


	public function updateOrCreate($data)
    {
        // $result = DB::table('onlineform_users')->updateOrInsert($key,$data);
        $result = DB::connection('online_request')->table('users')->insert($data);
       
        return $result;
    }

    


}
