<?php

namespace App\Http\Controllers\Memo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Memo\TardinessMemoMapper;
use Barryvdh\DomPDF\Facade\Pdf;

class TardinessMemoController extends Controller
{
    //
    private $mapper;

    public function __construct(TardinessMemoMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.memo.tardiness-memo.index');
    }

    public function list(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
            'search' => $request->input('search'),
        ];

        $result = $this->mapper->list($filter);

        return response()->json($result);
    }

    public function save(Request $request)
    {
        $data = json_decode($request->data);

        $data_arr = (array) $data;

        if($data_arr['id']=="" || $data_arr['id']==0  ){
            $result = $this->mapper->insertValid($data_arr);
        }else{
            $result = $this->mapper->updateValid($data_arr);
        }
            
        return response()->json($result);
    }

    public function readMemo(Request $request)
    {
        $result = $this->mapper->readMemo($request->id);

        return response()->json($result);
    }

    public function getNames(Request $request)
    {
        $result = $this->mapper->getNames();

        return response()->json($result);
    }

    public function print(Request $request)
    {

        // $period_id = $request->period_id;
        // $result = $this->mapper->getEmployeeForPrint($period_id,'semi');
        $memo = $this->mapper->find($request->id);
        
        $start = date('Y-m-d',strtotime($memo->memo_year.'-'.$memo->memo_month.'-01'));
        
        $filter = array(
            'from' => $start,
            'to' => date('Y-m-t',strtotime($start)),
        );

        $details = $this->mapper->getLates($memo->biometric_id,$filter);
       
        if($memo->memo_year==2023)
        {
            $manual = $this->mapper->getManualTardy($memo->biometric_id);

            if($manual)
            {
                $mtardy = (int) $manual->tardy_count;
            }else {
                $mtardy = 0;
            }
            $breakdown = '';

            switch($memo->memo_month)
            {
                case 1 :
                        $total = $mtardy;
                        $months = "January";

                    break;
                case 2 :
                        // $iStart = date('Y-m-d',strtotime($memo->memo_year.'-02-01'));

                        // $ifilter = array(
                        //     'from' => $iStart,
                        //     'to' => date('Y-m-t',strtotime($iStart)),
                        // );

                        // $secondMonth =  $this->mapper->getLates($memo->biometric_id,$ifilter);
                        if($mtardy>0){
                            $breakdown = "Last January you incurred a total of ($mtardy) tardiness occurrence.";
                        }
                        $total = $mtardy + count($details);
                        $months = "January, February";
                       
                    break;
                case 3 :

                        $iStart = date('Y-m-d',strtotime($memo->memo_year.'-02-01'));

                        $ifilter = array(
                            'from' => $iStart,
                            'to' => date('Y-m-t',strtotime($iStart)),
                        );

                        if($mtardy>0){
                            $breakdown = "Last January you incurred a total of ($mtardy) tardiness occurrence";
                        }

                        $secondMonth =  $this->mapper->getLates($memo->biometric_id,$ifilter);

                        if(count($secondMonth)>0){
                            if($mtardy>0){
                                $breakdown .= " and last February you incurred a total of (".count($secondMonth).") tardiness occurrence.";
                        
                            }else {
                                $breakdown  = "Last February you incurred a total of (".count($secondMonth).") tardiness occurrence.";
                            }
                        }else {
                            if($mtardy>0){
                                $breakdown .= ".";
                            }
                        }

                        $total = $mtardy + count($secondMonth) + count($details);
                        $months = "January, February, March";

                    break;
                case 4 :
                   

                    $total = count($details);
                    $months = "April";
                    break;
                case 5 :
                   
                    $march = date('Y-m-d',strtotime($memo->memo_year.'-04-01'));

                    $marchFilter = array(
                        'from' => $march,
                        'to' => date('Y-m-t',strtotime($march)),
                    );

                    $marchResult =  $this->mapper->getLates($memo->biometric_id,$marchFilter);


                    if(count($marchResult) >0)
                    {
                        $breakdown = "Last March you incurred a total of (".count($marchResult).") tardiness occurrence. ";
                    }

                    // $may = date('Y-m-d',strtotime($memo->memo_year.'-05-01'));

                    // $mayFilter = array(
                    //     'from' => $iStart,
                    //     'to' => date('Y-m-t',strtotime($iStart)),
                    // );

                    // $mayResult =  $this->mapper->getLates($memo->biometric_id,$mayFilter);

                    $total = count($marchResult) + count($details);
                    $months = "April, May";

                    break;
                case 6 :
                    $march = date('Y-m-d',strtotime($memo->memo_year.'-04-01'));
                    $breakdown = '';
                    $marchFilter = array(
                        'from' => $march,
                        'to' => date('Y-m-t',strtotime($march)),
                    );

                    $marchResult =  $this->mapper->getLates($memo->biometric_id,$marchFilter);
                
                    if(count($marchResult)>0){
                        $breakdown = "Last March you incurred a total of (".count($marchResult).") tardiness occurrence";
                    }

                    $may = date('Y-m-d',strtotime($memo->memo_year.'-05-01'));

                    $mayFilter = array(
                        'from' => $may,
                        'to' => date('Y-m-t',strtotime($may)),
                    );

                    $mayResult =  $this->mapper->getLates($memo->biometric_id,$mayFilter);

                    if(count($mayResult)>0){
                        if(count($marchResult)>0){
                            $breakdown .= " and last May you incurred a total of (".count($mayResult).") tardiness occurrence.";
                    
                        }else {
                            $breakdown  .= "Last May you incurred a total of (".count($mayResult).") tardiness occurrence.";
                        }
                    }else {
                        if(count($marchResult)>0){
                            $breakdown .= ".";
                        }
                    }

                    $total = count($marchResult) + count($mayResult) + count($details);
                    $months = "April, May, June";
                    
                    break;
                case 7 :
                    $total = count($details);
                    $months = "July";
                    break;
                  
                case 8 :
                    $july = date('Y-m-d',strtotime($memo->memo_year.'-07-01'));

                    $julyFilter = array(
                        'from' => $july,
                        'to' => date('Y-m-t',strtotime($july)),
                    );

                    $julyResult =  $this->mapper->getLates($memo->biometric_id,$julyFilter);

                 
                    if(count($julyResult) >0)
                    {
                        $breakdown = "Last July you incurred a total of (".count($julyResult).") tardiness occurrence. ";
                    }

                    // $may = date('Y-m-d',strtotime($memo->memo_year.'-05-01'));

                    // $mayFilter = array(
                    //     'from' => $iStart,
                    //     'to' => date('Y-m-t',strtotime($iStart)),
                    // );

                    // $mayResult =  $this->mapper->getLates($memo->biometric_id,$mayFilter);

                    $total = count($julyResult) + count($details);
                    $months = "July, August";

                    break;
                case 9 :
                    $july = date('Y-m-d',strtotime($memo->memo_year.'-07-01'));

                    $julyFilter = array(
                        'from' => $july,
                        'to' => date('Y-m-t',strtotime($july)),
                    );

                    $julyResult =  $this->mapper->getLates($memo->biometric_id,$julyFilter);
                
                    if(count($julyResult)>0){
                        $breakdown = "Last July you incurred a total of (".count($julyResult).") tardiness occurrence";
                    }

                    $aug = date('Y-m-d',strtotime($memo->memo_year.'-08-01'));

                    $augFilter = array(
                        'from' => $aug,
                        'to' => date('Y-m-t',strtotime($aug)),
                    );

                    $augResult =  $this->mapper->getLates($memo->biometric_id,$augFilter);

                    if(count($augResult)>0){
                        if(count($julyResult)>0){
                            $breakdown .= " and last August you incurred a total of (".count($augResult).") tardiness occurrence.";
                    
                        }else {
                            $breakdown  = "Last August you incurred a total of (".count($augResult).") tardiness occurrence.";
                        }
                    }else {
                        if(count($julyResult)>0){
                            $breakdown .= ".";
                        }
                    }

                    $total = count($julyResult) + count($augResult) + count($details);
                    $months = "July, August, September";
                    break;
                case 10 :
                    $total = count($details);
                    $months = "October";
                   
                    break;
                case 11 :
                    $oct = date('Y-m-d',strtotime($memo->memo_year.'-10-01'));

                    $octFilter = array(
                        'from' => $oct,
                        'to' => date('Y-m-t',strtotime($oct)),
                    );

                    $octResult =  $this->mapper->getLates($memo->biometric_id,$octFilter);

                 
                    if(count($octResult) >0)
                    {
                        $breakdown = "Last October you incurred a total of (".count($octResult).") tardiness occurrence. ";
                    }

                    $total = count($octResult) + count($details);
                    $months = "October, November";
                    break;
                case 12 :
                    $oct = date('Y-m-d',strtotime($memo->memo_year.'-10-01'));

                    $octFilter = array(
                        'from' => $oct,
                        'to' => date('Y-m-t',strtotime($oct)),
                    );

                    $octResult =  $this->mapper->getLates($memo->biometric_id,$octFilter);
                
                    if(count($octResult)>0){
                        $breakdown = "Last October you incurred a total of (".count($octResult).") tardiness occurrence";
                    }

                    $nov = date('Y-m-d',strtotime($memo->memo_year.'-11-01'));

                    $novFilter = array(
                        'from' => $nov,
                        'to' => date('Y-m-t',strtotime($nov)),
                    );

                    $novResult =  $this->mapper->getLates($memo->biometric_id,$novFilter);

                    if(count($novResult)>0){
                        if(count($octResult)>0){
                            $breakdown .= " and last November you incurred a total of (".count($novResult).") tardiness occurrence.";
                    
                        }else {
                            $breakdown  = "Last November you incurred a total of (".count($novResult).") tardiness occurrence.";
                        }
                    }else {
                        if(count($octResult)>0){
                            $breakdown .= ".";
                        }
                    }

                    $total = count($octResult) + count($novResult) + count($details);
                    $months = "October, November, December";
                    break;
            }
        }else{
            switch($memo->memo_month)
            {
                case 1 :
                    $breakdown = '';

                    $january = date('Y-m-d',strtotime($memo->memo_year.'-01-01'));

                    $total = count($details);
                    $months = "January";

                break;
                case 2 :
                    $breakdown = '';

                    $january = date('Y-m-d',strtotime($memo->memo_year.'-01-01'));

                    $janFilter = array(
                        'from' => $january,
                        'to' => date('Y-m-t',strtotime($january)),
                    );

                    $firstMonth =  $this->mapper->getLates($memo->biometric_id,$janFilter);

                    if(count($firstMonth)>0){
                        $breakdown .= "Last January you incurred a total of (".count($firstMonth).") tardiness occurrence";
                    }

                    $total = count($firstMonth) + count($details);

                    $months = "January, February";

                break;
                case 3 :

                        $breakdown = '';

                        $january = date('Y-m-d',strtotime($memo->memo_year.'-01-01'));

                        $janFilter = array(
                            'from' => $january,
                            'to' => date('Y-m-t',strtotime($january)),
                        );

                        $firstMonth =  $this->mapper->getLates($memo->biometric_id,$janFilter);

                        $february = date('Y-m-d',strtotime($memo->memo_year.'-02-01'));

                        $febFilter = array(
                            'from' => $february,
                            'to' => date('Y-m-t',strtotime($february)),
                        );

                        $secondMonth =  $this->mapper->getLates($memo->biometric_id,$febFilter);


                        $total = count($firstMonth) + count($secondMonth) + count($details);
                        $months = "January, February, March";

                        // $ifilter = array(
                        //     'from' => $iStart,
                        //     'to' => date('Y-m-t',strtotime($iStart)),
                        // );

                        if(count($firstMonth)>0){
                            $breakdown .= "Last January you incurred a total of (".count($firstMonth).") tardiness occurrence";
                        }

                        // $secondMonth =  $this->mapper->getLates($memo->biometric_id,$ifilter);

                        if(count($secondMonth)>0){
                            if(count($firstMonth)>0){
                                $breakdown .= " and last February you incurred a total of (".count($secondMonth).") tardiness occurrence.";
                        
                            }else {
                                $breakdown  = "Last February you incurred a total of (".count($secondMonth).") tardiness occurrence.";
                            }
                        }else {
                            if(count($firstMonth)>0){
                                $breakdown .= ".";
                            }
                        }
                       
                        $months = "January, February, March";

                break;
                case 4 :
                    $total = count($details);
                    $months = "April";
                    $breakdown = "";

                break;
                case 5 :
                   
                    $march = date('Y-m-d',strtotime($memo->memo_year.'-04-01'));

                    $marchFilter = array(
                        'from' => $march,
                        'to' => date('Y-m-t',strtotime($march)),
                    );

                    $marchResult =  $this->mapper->getLates($memo->biometric_id,$marchFilter);


                    if(count($marchResult) >0)
                    {
                        $breakdown = "Last April you incurred a total of (".count($marchResult).") tardiness occurrence. ";
                    }else{
                        $breakdown = '';
                    }


                    $total = count($marchResult) + count($details);
                    $months = "April, May";

                    break;
                case 6 :
                    $march = date('Y-m-d',strtotime($memo->memo_year.'-04-01'));
                    $breakdown = '';
                    $marchFilter = array(
                        'from' => $march,
                        'to' => date('Y-m-t',strtotime($march)),
                    );

                    $marchResult =  $this->mapper->getLates($memo->biometric_id,$marchFilter);
                
                    if(count($marchResult)>0){
                        $breakdown = "Last March you incurred a total of (".count($marchResult).") tardiness occurrence";
                    }

                    $may = date('Y-m-d',strtotime($memo->memo_year.'-05-01'));

                    $mayFilter = array(
                        'from' => $may,
                        'to' => date('Y-m-t',strtotime($may)),
                    );

                    $mayResult =  $this->mapper->getLates($memo->biometric_id,$mayFilter);

                    if(count($mayResult)>0){
                        if(count($marchResult)>0){
                            $breakdown .= " and last May you incurred a total of (".count($mayResult).") tardiness occurrence.";
                    
                        }else {
                            $breakdown  = "Last May you incurred a total of (".count($mayResult).") tardiness occurrence.";
                        }
                    }else {
                        if(count($marchResult)>0){
                            $breakdown .= ".";
                        }
                    }

                    $total = count($marchResult) + count($mayResult) + count($details);
                    $months = "April, May, June";
                    
                    break;
                case 7 :
                    $total = count($details);
                    $months = "July";
                    break;
                  
                case 8 :
                    $july = date('Y-m-d',strtotime($memo->memo_year.'-07-01'));

                    $julyFilter = array(
                        'from' => $july,
                        'to' => date('Y-m-t',strtotime($july)),
                    );

                    $julyResult =  $this->mapper->getLates($memo->biometric_id,$julyFilter);

                 
                    if(count($julyResult) >0)
                    {
                        $breakdown = "Last July you incurred a total of (".count($julyResult).") tardiness occurrence. ";
                    }

                    $total = count($julyResult) + count($details);
                    $months = "July, August";

                    break;
                case 9 :
                    $july = date('Y-m-d',strtotime($memo->memo_year.'-07-01'));

                    $julyFilter = array(
                        'from' => $july,
                        'to' => date('Y-m-t',strtotime($july)),
                    );

                    $julyResult =  $this->mapper->getLates($memo->biometric_id,$julyFilter);
                
                    if(count($julyResult)>0){
                        $breakdown = "Last July you incurred a total of (".count($julyResult).") tardiness occurrence";
                    }

                    $aug = date('Y-m-d',strtotime($memo->memo_year.'-08-01'));

                    $augFilter = array(
                        'from' => $aug,
                        'to' => date('Y-m-t',strtotime($aug)),
                    );

                    $augResult =  $this->mapper->getLates($memo->biometric_id,$augFilter);

                    if(count($augResult)>0){
                        if(count($julyResult)>0){
                            $breakdown .= " and last August you incurred a total of (".count($augResult).") tardiness occurrence.";
                    
                        }else {
                            $breakdown  = "Last August you incurred a total of (".count($augResult).") tardiness occurrence.";
                        }
                    }else {
                        if(count($julyResult)>0){
                            $breakdown .= ".";
                        }
                    }

                    $total = count($julyResult) + count($augResult) + count($details);
                    $months = "July, August, September";
                    break;
                case 10 :
                    $total = count($details);
                    $months = "October";
                   
                    break;
                case 11 :
                    $oct = date('Y-m-d',strtotime($memo->memo_year.'-10-01'));

                    $octFilter = array(
                        'from' => $oct,
                        'to' => date('Y-m-t',strtotime($oct)),
                    );

                    $octResult =  $this->mapper->getLates($memo->biometric_id,$octFilter);

                 
                    if(count($octResult) >0)
                    {
                        $breakdown = "Last October you incurred a total of (".count($octResult).") tardiness occurrence. ";
                    }

                    $total = count($octResult) + count($details);
                    $months = "October, November";
                    break;
                case 12 :
                    $oct = date('Y-m-d',strtotime($memo->memo_year.'-10-01'));

                    $octFilter = array(
                        'from' => $oct,
                        'to' => date('Y-m-t',strtotime($oct)),
                    );

                    $octResult =  $this->mapper->getLates($memo->biometric_id,$octFilter);
                
                    if(count($octResult)>0){
                        $breakdown = "Last October you incurred a total of (".count($octResult).") tardiness occurrence";
                    }

                    $nov = date('Y-m-d',strtotime($memo->memo_year.'-11-01'));

                    $novFilter = array(
                        'from' => $nov,
                        'to' => date('Y-m-t',strtotime($nov)),
                    );

                    $novResult =  $this->mapper->getLates($memo->biometric_id,$novFilter);

                    if(count($novResult)>0){
                        if(count($octResult)>0){
                            $breakdown .= " and last November you incurred a total of (".count($novResult).") tardiness occurrence.";
                    
                        }else {
                            $breakdown  = "Last November you incurred a total of (".count($novResult).") tardiness occurrence.";
                        }
                    }else {
                        if(count($octResult)>0){
                            $breakdown .= ".";
                        }
                    }

                    $total = count($octResult) + count($novResult) + count($details);
                    $months = "October, November, December";
                    break;
            }
                
        }

        $total_str = "A total of ($total) occurrence for ($months) period(s).";
      
        $pdf = PDF::loadView('app.memo.tardiness-memo.print',['data' => $memo,'details' => $details,'total' => $total_str,'breakdown' => $breakdown, 'total_count' => $total ])->setPaper('letter','portrait');

        //$pdf->output();
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        
        //$canvas = $dom_pdf ->get_canvas();
        //$canvas->page_text(510, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        //return $pdf->download('JLR-DTR-Print.pdf'); 
        return $pdf->stream('TardinessMemo.pdf'); 

    }

    public function getYear()
    {
        $result = $this->mapper->getYear();

        return response()->json($result);
    }
}
