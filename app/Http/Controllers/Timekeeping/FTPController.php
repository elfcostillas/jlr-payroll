<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\FTPMapper;
use Illuminate\Support\Facades\Auth;

use App\Mappers\TimeKeepingMapper\FailureToPunchV2Mapper;
class FTPController extends Controller
{
    //
    private $mapper;
    private $mapper2;

    public function __construct(FTPMapper $mapper,FailureToPunchV2Mapper $mapper2)
    {
        $this->mapper = $mapper;
        $this->mapper2 = $mapper2;
    }

    public function index()
    {
        return view('app.timekeeping.ftp.index');
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

        $result = $this->mapper2->list($filter);
        
        return response()->json($result);
    }

    public function create(Request $request){

    }

    public function update(Request $request)
    {
        $result = $this->mapper2->updateValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function approve(Request $request)
    {
        $data = array(
            'id' => $request->ftp_id,
            'hr_received' => 'Y',
            'hr_received_by'=>  Auth::user()->id,
            'hr_received_on' => now()
        );

        $result = $this->mapper->updateValid($data);

        if(!is_object($result)){
            $this->mapper->insertRaw($result);
        }

        return response()->json(['success'=>'FTP Approved.']);
    }

    public function getEmployees() 
    {
        $result = $this->mapper2->getEmployees();

        return response()->json($result);
    }

    public function save(Request $request)
    {
        $user = Auth::user();

        $data = json_decode($request->data);

        $data_arr = (array) $data;

        if($data_arr['id']==null || $data_arr['id']==0 || $data_arr['id']=='')
        {
            $data_arr['created_by'] = $user->id;
            $data_arr['created_on'] = now(); 
           
            $data_arr['ftp_status'] = 'DRAFT'; 
            $result = $this->mapper2->insertValid($data_arr);
        }else {

            $result = $this->mapper2->updateValid($data_arr);
            if(!is_object($result)){
                $this->mapper2->post($data_arr);
            }
        }

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    function readByID(Request $request)
    {
        $result = $this->mapper2->find($request->id);
        return response()->json($result);
    }




}
