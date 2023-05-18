<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\FTPMapper;
use Illuminate\Support\Facades\Auth;

class FTPController extends Controller
{
    //
    private $mapper;

    public function __construct(FTPMapper $mapper)
    {
        $this->mapper = $mapper;
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

        $result = $this->mapper->list($filter);

        return response()->json($result);
    }

    public function create(Request $request){

    }

    public function update(Request $request)
    {
        
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




}
