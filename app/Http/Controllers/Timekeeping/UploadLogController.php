<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mappers\TimeKeepingMapper\UploadLogMapper;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class UploadLogController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index()
    {
        return view('app.timekeeping.upload-logs.index');
    }

    public function upload(Request $request)
    {
        $file = $request->file('files');
        
        $logs = [];

        $path = Storage::disk('local')->put('uploads',$file);
       
        $thisFile = Storage::get($path);
        $content = explode("\n",mb_convert_encoding($thisFile, 'UTF-8', 'UTF-8'));

        foreach($content as $line)
        {
            $data = preg_split("/\s+/", trim($line));
            var_dump($data);
            
            //array_push($log,['punch_date'=> $data[''],'punch_time' => $data[''], 'biometric_id' => $data[''] ]);
        }

        return true;
    }
}

/*
public function dtrupload(Request $request){
		$user = Auth::user();
		
		$file = $request->file('files');
		//$file= mb_convert_encoding($file, 'UTF-8', 'UTF-8');
		$filename = rand(); //getClientOriginalName
		$new_name = $filename . '.' . $file->getClientOriginalExtension();
		
		$path = Storage::disk('local')->put('txt',$file);
	
		//$data['name'] = mb_convert_encoding($data['name'], 'UTF-8', 'UTF-8');
		$thisFile = File::get('uploads/'.$path);
		$content = explode("\n",mb_convert_encoding($thisFile, 'UTF-8', 'UTF-8'));

		$this->tmp->clearTmpByUID($user->user_id);
		foreach ($content as $key => $value) {
			$line = str_getcsv($value,",");
			if($line[0]!=''){
				$data = [
					'emp_idno'=>$line[0],
					'date'=>$line[2],
					'time_in'=>$line[3],
					'time_out'=>$line[4],
					'hrs'=>$line[5],
					'late'=>$line[6],
					'ut'=>$line[7],
					'ot'=>$line[8],
					'nd'=>$line[9],
					'uid'=>$user->user_id
				];
				$this->tmp->insertValid($data);	
			}
			

		}
		$this->dtrload($user->user_id);

		return response()->json($content);
     	// return view('uploader.index'); 
	}*/