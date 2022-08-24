<?php

namespace App\Http\Controllers\EmployeeFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;

class EmployeeController extends Controller
{
    //
    private $mapper;

    public function __construct(EmployeeMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.employee-file.employee-master-data.index');
    }

    public function list(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->mapper->list($filter);

        return response()->json($result);
    }

    // public function create(Request $request)
    // {
    //     $result = $this->mapper->insertValid($request->all());

    //     if(is_object($result)){
	// 		return response()->json($result)->setStatusCode(500, 'Error');
	// 	}

    //     return response()->json($result);
    // }

    // public function update(Request $request)
    // {
    //     $result = $this->mapper->updateValid($request->all());

    //     if(is_object($result)){
	// 		return response()->json($result)->setStatusCode(500, 'Error');
	// 	}

    //     return response()->json($result);
    // }

    public function save(Request $request)
    {
        $data = json_decode($request->data);

        $data_arr = (array) $data;

        if($data_arr['id']==null){
            $result = $this->mapper->insertValid($data_arr);
        }else{
            $result = $this->mapper->updateValid($data_arr);
        }

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }


    public function readById(Request $request)
    {
        $result = $this->mapper->header($request->id);
        return response()->json($result);
    }

   

}


/*
$data = json_decode($request->data);
    $user = Auth::user();
    //dd(date_format(Carbon::createFromFormat('m/d/Y',$data->po_date),'Y-m-d'));
    $data->po_date = date_format(Carbon::createFromFormat('m/d/Y',$data->po_date),'Y-m-d');
    $data = (array) $data;

    if(is_object($data['po_supplier'])){
        $data['po_supplier']=$data['po_supplier']->contact_id;
    }
    */