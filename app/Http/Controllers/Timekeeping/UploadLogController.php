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
	private $mapper;

    public function __construct(UploadLogMapper $mapper)
    {
		$this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.upload-logs.index');
    }

    public function upload(Request $request)
    {
        $file = $request->file('files');
        
        $logs = [];
        $dtr = [];

        $path = Storage::disk('local')->put('uploads',$file);
       
        $thisFile = Storage::get($path);
        $content = explode("\n",mb_convert_encoding($thisFile, 'UTF-8', 'UTF-8'));

		// $new_content = array_chunk($content,10000);

		foreach($content as $line)
		{
			$data = preg_split("/\s+/", trim($line));

			if( $data[0] !="AC-No." && $data[0] !="" ){
				if(count($data)==5){
					if($data[4]!=""){
						$data[3] = $data[4];
					}

					$date = date_format(Carbon::createFromFormat('m/d/Y',$data[1]),'Y-m-d');
					array_push($logs,['punch_date'=> $date,'punch_time' => $data[2], 'biometric_id' => $data[0],'cstate' => $data[3] ]);
					//array_push($dtr,['dtr_date' => $date,'bio_metric_id' => $data[0]]);
				}else{
					if(count($data)==4){
						$date = date_format(Carbon::createFromFormat('m/d/Y',$data[1]),'Y-m-d');
						array_push($logs,['punch_date'=> $date,'punch_time' => $data[2], 'biometric_id' => $data[0],'cstate' => $data[3] ]);
					}
				}
				
			}

			
		}

		$result = $this->mapper->insertDB($logs);
        //$result2 = $this->edtr->insertDB($dtr);
		
        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);

    }

	public function index_csv()
	{
		return view('app.timekeeping.upload-csv.index');
	}
	
	public function  upload_csv(Request $request)
	{
		$file = $request->file('files');
        
        $logs = [];
        $dtr = [];

        $path = Storage::disk('local')->put('uploads',$file);
       
        $thisFile = Storage::get($path);
        $content = explode("\n",mb_convert_encoding($thisFile, 'UTF-8', 'UTF-8'));

        foreach($content as $line)
        {
			$data = str_getcsv($line,",");

			if(array_key_exists(48,$data)){
				if($data[48]=='Y'){
					$date = date_format(Carbon::createFromFormat('m/d/Y',$data[3]),'Y-m-d');
					
					$key = array('biometric_id' => $data[0],'dtr_date' => $date);

					$formatted = array(
						
						'time_in' => ($data[5]=='') ? null : $data[5],
						'time_out' => ($data[6]=='') ? null : $data[6],
						'late' => ($data[8]=='') ? 0 : $data[8],
						'late_eq' => ($data[9]=='') ? 0 : $data[9],
						'under_time' => ($data[10]=='') ? 0 : $data[10],
						'over_time' => ($data[14]=='') ? 0 : $data[14],
						'night_diff' => ($data[11]=='') ? 0 : $data[11],
						'night_diff_ot' => ($data[15]=='') ? 0 : $data[15],
						'ndays' => ($data[7]=='') ? 0 : $data[7],
						'ot_in' => ($data[12]=='') ? null : $data[12],
						'ot_out' => ($data[13]=='') ? null : $data[13],
						'restday_hrs' => ($data[16]=='') ? 0 : $data[16],
						'restday_ot' => ($data[17]=='') ? 0 : $data[17],
						'restday_nd' => ($data[18]=='') ? 0 : $data[18],
						'restday_ndot' => ($data[19]=='') ? 0 : $data[19],
						'reghol_pay' => ($data[20]=='') ? 0 : $data[20],
						'reghol_hrs' => ($data[22]=='') ? 0 : $data[22],
						'reghol_ot' => ($data[23]=='') ? 0 : $data[23],
						'reghol_rd' => ($data[24]=='') ? 0 : $data[24],
						'reghol_rdnd' => ($data[25]=='') ? 0 : $data[25],
						'reghol_rdot' => ($data[26]=='') ? 0 : $data[26],
						'reghol_nd' => ($data[27]=='') ? 0 : $data[27],
						'reghol_ndot' => ($data[28]=='') ? 0 : $data[28],
						'reghol_rdndot' => ($data[29]=='') ? 0 : $data[29],
						'sphol_pay' => ($data[30]=='') ? 0 : $data[30],
						'sphol_hrs' => ($data[31]=='') ? 0 : $data[31],
						'sphol_ot' => ($data[32]=='') ? 0 : $data[32],
						'sphol_rd' => ($data[33]=='') ? 0 : $data[33],
						'sphol_rdnd' => ($data[34]=='') ? 0 : $data[34],
						'sphol_rdot' => ($data[35]=='') ? 0 : $data[35],
						'sphol_nd' => ($data[36]=='') ? 0 : $data[36],
						'sphol_ndot' => ($data[37]=='') ? 0 : $data[37],
						'sphol_rdndot' => ($data[38]=='') ? 0 : $data[38],
						'dblhol_pay' => ($data[39]=='') ? 0 : $data[39],
						'dblhol_hrs' => ($data[40]=='') ? 0 : $data[40],
						'dblhol_ot' => ($data[41]=='') ? 0 : $data[41],
						'dblhol_rd' => ($data[42]=='') ? 0 : $data[42],
						'dblhol_rdnd' => ($data[43]=='') ? 0 : $data[43],
						'dblhol_rdot' => ($data[44]=='') ? 0 : $data[44],
						'dblhol_nd' => ($data[45]=='') ? 0 : $data[45],
						'dblhol_ndot' => ($data[46]=='') ? 0 : $data[46],
						'dblhol_rdndot' => ($data[47]=='') ? 0 : $data[47]
					);
		
					$result = $this->mapper->updatefromCSV($key,$formatted);
				}
			}
			

        }
		
		//$result = $this->mapper->insertDB_CSV($logs);
       
        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
	}

	public function index_summary()
	{
		return view('app.timekeeping.upload-summary.index');
	}

	public function upload_summary(Request $request)
	{
		//dd($request->period_id);
		$file = $request->file('files');
        
        $logs = [];
        $dtr = [];

        $path = Storage::disk('local')->put('uploads',$file);
       
        $thisFile = Storage::get($path);
        $content = explode("\n",mb_convert_encoding($thisFile, 'UTF-8', 'UTF-8'));

		$inProgress = $this->mapper->getPeriodInProgress();

		$whatHappened = '';

        foreach($content as $line)
        {
			$data = str_getcsv($line,",");

			if($data && $data[0]==$inProgress->id)
			{

				
				$key = array('biometric_id' => $data[1],'period_id' => $data[0]);

				$formatted = array(
						'ndays' => ($data[3]=='') ? 0 : $data[3],
						// 'late' => ($data[8]=='') ? 0 : $data[8],
						'late_eq' => ($data[4]=='') ? 0 : $data[4],
						'under_time' => ($data[5]=='') ? 0 : $data[5],
						'night_diff' => ($data[6]=='') ? 0 : $data[6],
						'over_time' => ($data[7]=='') ? 0 : $data[7],
						
						'night_diff_ot' => ($data[8]=='') ? 0 : $data[8],
						'restday_hrs' => ($data[9]=='') ? 0 : $data[9],
						'restday_ot' => ($data[10]=='') ? 0 : $data[10],
						'restday_nd' => ($data[11]=='') ? 0 : $data[11],
						'restday_ndot' => ($data[12]=='') ? 0 : $data[12],
						'reghol_pay' => ($data[13]=='') ? 0 : $data[13],
						'reghol_hrs' => ($data[14]=='') ? 0 : $data[14],
						'reghol_ot' => ($data[15]=='') ? 0 : $data[15],
						'reghol_rd' => ($data[16]=='') ? 0 : $data[16],
						'reghol_rdnd' => ($data[17]=='') ? 0 : $data[17],
						'reghol_rdot' => ($data[18]=='') ? 0 : $data[18],
						'reghol_nd' => ($data[19]=='') ? 0 : $data[19],
						'reghol_ndot' => ($data[20]=='') ? 0 : $data[20],
						'reghol_rdndot' => ($data[21]=='') ? 0 : $data[21],
						'sphol_pay' => ($data[22]=='') ? 0 : $data[22],
						'sphol_hrs' => ($data[23]=='') ? 0 : $data[23],
						'sphol_ot' => ($data[24]=='') ? 0 : $data[24],
						'sphol_rd' => ($data[25]=='') ? 0 : $data[25],
						'sphol_rdnd' => ($data[26]=='') ? 0 : $data[26],
						'sphol_rdot' => ($data[27]=='') ? 0 : $data[27],
						'sphol_nd' => ($data[28]=='') ? 0 : $data[28],
						'sphol_ndot' => ($data[29]=='') ? 0 : $data[29],
						'sphol_rdndot' => ($data[30]=='') ? 0 : $data[30],
						'dblhol_pay' => ($data[31]=='') ? 0 : $data[31],
						'dblhol_hrs' => ($data[32]=='') ? 0 : $data[32],
						'dblhol_ot' => ($data[33]=='') ? 0 : $data[33],
						'dblhol_rd' => ($data[34]=='') ? 0 : $data[34],
						'dblhol_rdnd' => ($data[35]=='') ? 0 : $data[35],
						'dblhol_rdot' => ($data[36]=='') ? 0 : $data[36],
						'dblhol_nd' => ($data[37]=='') ? 0 : $data[37],
						'dblhol_ndot' => ($data[38]=='') ? 0 : $data[38],
						'dblhol_rdndot' => ($data[39]=='') ? 0 : $data[39],

						'dblsphol_pay' => ($data[40]=='') ? 0 : $data[40],
						'dblsphol_hrs' => ($data[41]=='') ? 0 : $data[41],
						'dblsphol_ot' => ($data[42]=='') ? 0 : $data[42],
						'dblsphol_rd' => ($data[43]=='') ? 0 : $data[43],
						'dblsphol_rdnd' => ($data[44]=='') ? 0 : $data[44],
						'dblsphol_rdot' => ($data[45]=='') ? 0 : $data[45],
						'dblsphol_nd' => ($data[46]=='') ? 0 : $data[46],
						'dblsphol_ndot' => ($data[47]=='') ? 0 : $data[47],
						'dblsphol_rdndot' => ($data[48]=='') ? 0 : $data[48]
				);

				$result = $this->mapper->updateSummary($key,$formatted);
				// $whatHappened .= 'it came here '.json_encode($key);
			}else{
				//$whatHappened = 'it didnt came here';
			}

			
		}

		return $whatHappened;
	}	

	public function upload_weekly(Request $request)
	{

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
	}
	
	SELECT DISTINCT biometric_id 
FROM payroll_period 
INNER JOIN edtr_raw ON  edtr_raw.punch_date BETWEEN payroll_period.date_from AND payroll_period.date_to
WHERE payroll_period.id =1;


*/