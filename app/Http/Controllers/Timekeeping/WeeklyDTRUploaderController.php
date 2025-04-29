<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Mappers\TimeKeepingMapper\UploadLogMapper;

class WeeklyDTRUploaderController extends Controller
{
    //
    private $mapper;

    public function __construct(UploadLogMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.upload-logs.index-weekly');
    }

    public function upload(Request $request)
    {
        $file = $request->file('files');
        
        $logs = [];
        $dtr = [];

        $path = Storage::disk('local')->put('uploads',$file);
       
        $thisFile = Storage::get($path);
        $content = explode("\n",mb_convert_encoding($thisFile, 'UTF-8', 'UTF-8'));

        foreach($content as $line)
		{
			// $data = preg_split("/\s+/", trim($line));
            $data = str_getcsv($line,",");

			// if( $data[0] !="AC-No." && $data[0] !="" ){
			// 	if(count($data)==5){
			// 		if($data[4]!=""){
			// 			$data[3] = $data[4];
			// 		}

			// 		$date = date_format(Carbon::createFromFormat('m/d/Y',$data[1]),'Y-m-d');
			// 		array_push($logs,['punch_date'=> $date,'punch_time' => $data[2], 'biometric_id' => $data[0],'cstate' => $data[3] ]);
			// 		//array_push($dtr,['dtr_date' => $date,'bio_metric_id' => $data[0]]);
			// 	}else{
			// 		if(count($data)==4){
			// 			$date = date_format(Carbon::createFromFormat('m/d/Y',$data[1]),'Y-m-d');
			// 			array_push($logs,['punch_date'=> $date,'punch_time' => $data[2], 'biometric_id' => $data[0],'cstate' => $data[3] ]);
			// 		}
			// 	}
				
			// }

            
            if(strlen($data[0]) <= 5 && $data[0] !="" && $data[2]!=""){

                $date = Carbon::createFromFormat('m/d/Y',$data[2])->format('Y-m-d');

                $keys = [
                    'biometric_id' => $data[0],
                    'dtr_date' => $date,
                ];

                $body = [
                    'time_in' => ($data[3] == '') ? null : $data[3],
                    'time_out' => ($data[4] == '') ? null : $data[4],
                    'ndays' => $data[5],
                    'over_time' => $data[6],
                ];

                $result = $this->mapper->updateOrCreate($keys,$body);
                
            }
        }

        return true;
    }


}
