<?php

namespace App\Mappers\SettingsMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SSSTableMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\SSSTable';
    protected $rules = [
    	
    ];

    public function list($filter)
    {

        $result = $this->model->select();

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('range1','asc');

		return [
			'total' => $total,
			'data' => $result->get()
		];


        //$result = $this->model->select();
    }

}
