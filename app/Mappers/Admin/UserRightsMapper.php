<?php

namespace App\Mappers\Admin;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserRightsMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Admin\UserRights';
    protected $rules = [
    	
    ];

	public function showAllUsers($filter)
	{
		$result = $this->model->select('id','name','email','super_user')
		->from('users');

		if($filter['filter']!=null){
            foreach($filter['filter']['filters'] as $f)
            {
                $result->where($f['field'],'like','%'.$f['value'].'%');
            }
        }

        $total = $result->count();

        $result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('name','asc');

    	return [
            'total' => $total,
            'data' => $result->get()
        ];

	}

	public function showAllRights()
	{
		//select line_id,menu_desc from main_menu;
		$result = $this->model->select('id','menu_desc')->from('main_menu')->get();

		foreach($result as $main)
		{
			$main->sub = $this->model->select('id','sub_menu_desc','sub_menu_main')->from('sub_menu')
			->where('sub_menu_main',$main->id)->get();
		}

		return $result;
	}

	public function getUsersRights($urights)
	{
		
	}

	public function delete($data)
	{
		$result = $this->model->from('user_rights')->where($data)->delete();

		return $result;
	}

    public function getRights($urights)
	{
		$result = $this->model->select('sub_menu_id')->from('user_rights')->where('user_id',$urights);

		return $result->get();
	}

}
//sub_menu_id from user_rights user_id
//select username,user_firstname,user_lastname from users;
/*
                select distinct main_menu.menu_desc from user_rights 
inner join sub_menu on sub_menu_id = sub_menu.line_id 
inner join main_menu on main_id = main_menu.line_id
WHERE user_rights.user_id = 1;

select sub_menu.menu_desc,sub_menu.menu_url from user_rights 
inner join sub_menu on sub_menu_id = sub_menu.line_id 
inner join main_menu on main_id = main_menu.line_id
WHERE user_rights.user_id = 1
and sub_menu.main_id = 1
*/