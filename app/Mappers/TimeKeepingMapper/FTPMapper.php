<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FTPMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\FTP';
    protected $rules = [
      
    ];

    // public function header($id){
	// 	return $this->model->find($id);
    // }

    public function list($filter)
    {
        // $result = $this->model->select()
        // ->from('manual_dtr')
        // ->join('employees','manual_dtr.biometric_id','=','employees.biometric_id')
        // ->join('users','encoded_by','=','users.id');

        // if($filter['filter']!=null){
		// 	foreach($filter['filter']['filters'] as $f)
		// 	{
		// 		$result->where($f['field'],'like','%'.$f['value'].'%');
		// 	}
		// }

		// $total = $result->count();

		// $result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('id','DESC');

        $result = $this->model->select()
            ->from('ftp')
            ->join('employee_names_vw','ftp.biometric_id','=','employee_names_vw.biometric_id')
            ->where([
                ['ftp_status','=','Approved'],
                ['hr_received','=','N'],
            ]);

        $total = $result->count();

        $result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('id','DESC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }

    function insertRaw($ftp_id){
        $ftp = $this->model->find($ftp_id);

        switch($ftp->ftp_state){
           
            case 'C/InOT':
                    $state = 'OT/In';
                break;

            case 'C/OutOT':
                    $state = 'OT/Out';
                break;

            default :
                    $state = $ftp->ftp_state;
                break;

        }

        $array = array(
            'punch_date' => $ftp->ftp_date,
            'punch_time' => $ftp->ftp_time,
            'biometric_id' => $ftp->biometric_id,
            'cstate' => $state,
            'src' => 'ftp'
        );

        DB::table('edtr_raw')->insert($array);
        
    }
}