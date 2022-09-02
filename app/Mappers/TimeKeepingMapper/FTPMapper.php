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
        $result = $this->model->select()
        ->from('manual_dtr')
        ->join('employees','manual_dtr.biometric_id','=','employees.biometric_id')
        ->join('users','encoded_by','=','users.id');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('id','DESC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }
}