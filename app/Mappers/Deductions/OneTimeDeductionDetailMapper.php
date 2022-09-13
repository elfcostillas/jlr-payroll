<?php

namespace App\Mappers\Deductions;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OneTimeDeductionDetailMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Deductions\OneTimeDeductionDetail';
    protected $rules = [
    	'header_id' => 'required|sometimes',
        'biometric_id' => 'required|sometimes',
        'amount' => 'required|sometimes'
    ];

    public function list($header_id)
    {
        // $result = $this->model->select(DB::raw("deduction_onetime_details.*,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname"))
        //             ->from('deduction_onetime_details')
        //             ->join('employees','deduction_onetime_details.biometric_id','=','employees.biometric_id')
        //             ->where('header_id',$header_id);
        $result = $this->model->select(DB::raw("employee_names_vw.biometric_id,employee_name as empname,location_name,header_id,ifnull(amount,0.00) as amount,line_id "))
                    ->from('employee_names_vw')
                    ->join('employees','employee_names_vw.biometric_id','=','employees.biometric_id')
                    ->leftJoin('locations','employees.location_id','=','locations.id')
                    ->leftJoin('deduction_onetime_details','employees.biometric_id',"=","deduction_onetime_details.biometric_id")
                    ->where('employee_names_vw.exit_status',1)
                    ->where('header_id',$header_id)
                    ->orWhere(function($query) { 
                        $query->whereNull('header_id');
                        $query->where('employee_names_vw.exit_status',1);
                    })
                    ->orderBy('lastname')
                    ->orderBy('firstname');

        return $result->get();
    }

   
    /*
    
SELECT deduction_onetime_details.*,CONCAT(IFNULL(lastname,''),IFNULL(firstname,''),IFNULL(suffixname,'')) AS empname FROM deduction_onetime_details 
INNER JOIN employees ON deduction_onetime_details.biometric_id = employees.biometric_id;


SELECT employee_names_vw.biometric_id,employee_name,location_name,header_id,amount,line_id 
FROM employee_names_vw 
INNER JOIN employees ON employee_names_vw.biometric_id = employees.biometric_id
LEFT JOIN locations ON employees.location_id = locations.id
LEFT JOIN deduction_onetime_details ON employees.biometric_id = deduction_onetime_details.biometric_id
WHERE employee_names_vw.exit_status=1
AND header_id =13 OR header_id IS NULL
ORDER BY lastname,firstname;

*/

}
