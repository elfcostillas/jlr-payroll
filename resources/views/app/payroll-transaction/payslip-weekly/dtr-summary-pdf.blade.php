<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        * {
            font-size : 8pt;
        }

        #header {
            position : fixed;
            top : -40px;
        }

        @page {
            margin-top : 60px;
            border : 1px solid red;
        }
    </style>
</head>
<body>
    
<?php
    use Carbon\Carbon;

    $line = [];
   
?>
<div id="header"><b>DTR - SUPPORT GROUP </b> <br>
Period : {{ $period_label->drange }}
</div>

@foreach($data as $emp)


<div style="page-break-inside : avoid;margin-top:0px;">
    <?php
        $ctr = 1;

        $no_of_days = 0;
        $total_ot = 0;

        $totalPerEmp = 0; 

    ?>
    <table border=1 style= "margin-bottom : 4px;border-collapse:collapse;margin-bottom:12px;" >
    
        <tr>
            <td colspan="3" style="font-weight:bold;padding-left:4px;" > Name : {{ $emp->employee_name }} </td>
            
            <td colspan=2 style="text-align:center;">Daily Rate : {{ number_format($emp->basic_salary,2) }}</td>
           
        </tr>
        <tr> 
            <td colspan="5"  style="padding-left:4px;"> {{ $emp->dept_name }} - {{ $emp->job_title_name }} </td>
        </tr> 
        <tr>
         
            <td style="text-align:center; width:80px;" > Date </td>
            <td style="text-align:center; width:80px;" >Time In</td>
            <td style="text-align:center; width:80px;" >Time Out</td>
            <td style="text-align:center; width:80px;" > Day </td>
            @foreach($headers as $key => $h)
               
                <td style="text-align:center;width:100px;"> {{ $label[$key]}} (Hrs)</td>
            @endforeach
        </tr>
        <tr>

            @foreach($emp->dtr as $dtr)
                
                    @php $date = Carbon::createFromFormat('Y-m-d',$dtr->dtr_date) @endphp
                    <td style="text-align:center;" >{{ $date->format('m/d/Y') }}</td>
                    <td style="text-align:center;" >{{ ($dtr->time_in !='' && $dtr->time_in != '00:00') ? $dtr->time_in : '' }}</td>
                    <td style="text-align:center;" >{{ ($dtr->time_out !='' && $dtr->time_out != '00:00') ? $dtr->time_out : '' }}</td>
                    <td style="text-align:center;" >{{ ($dtr->ndays>0) ? $dtr->ndays : '' }}</td>
                    @foreach($headers as $key => $h)
                        <td style="text-align:center;">{{ ($dtr->$key>0) ? $dtr->$key : '' }}</td>

                        <?php
                            if(isset($line[$emp->biometric_id][$key])){
                                $line[$emp->biometric_id][$key] +=  ($dtr->$key>0) ? $dtr->$key : 0;
                            }else {
                                $line[$emp->biometric_id][$key] = 0;
                                $line[$emp->biometric_id][$key] +=  ($dtr->$key>0) ? $dtr->$key : 0;
                            }
                            $total_ot += $dtr->$key;
                           
                        ?>
                    @endforeach
                    
               
               @php
                            $ctr++;

               @endphp
               @if($ctr<count($emp->dtr))
               </tr>
                <tr>
                    
                @else
                </tr>
               @endif
                <?php
                    if(isset($line[$emp->biometric_id]['ndays'])){
                        $line[$emp->biometric_id]['ndays'] += ($dtr->ndays>0) ? $dtr->ndays : 0;
                    }else{
                        $line[$emp->biometric_id]['ndays'] = 0;
                        $line[$emp->biometric_id]['ndays'] += ($dtr->ndays>0) ? $dtr->ndays : 0;
                    }

                    $no_of_days = $line[$emp->biometric_id]['ndays'] ;
                    
                ?>
            @endforeach
        <!-- </tr> -->
        <tr>
            <td></td>
            <td></td>
            <td style="text-align:right;padding-right:8px;" > <b>TOTAL</b></td>
            <td style="text-align:center;" >{{  $no_of_days }}</td>
            <td style="text-align:center;" >{{  $total_ot }}</td>
        </tr>
        
            <?php 
            
                $amount = compute('ndays',$no_of_days,$emp->basic_salary,$emp->retired);
                $ot_amount = compute('over_time',$total_ot,$emp->basic_salary,$emp->retired);
                $totalPerEmp += $amount; 
            ?>
   
        <tr> 
            <td>&nbsp;</td>
            <td></td>
            <td style="text-align:right;padding-right:8px;" > <b>TOTAL PAY</b></td>
            <td style="text-align:right;padding-right:8px;" >{{ ($amount>0) ? number_format($amount,2) : '' }}</td>
            <td style="text-align:right;padding-right:8px;" >{{ ($ot_amount>0) ? number_format($ot_amount,2) : '' }}</td>
        </tr> 
        <tr>
            <td colspan=3 style="text-align:right;padding-right:8px;font-weight:bold;">TOTAL NET PAY</td>            
            <td colspan=2 style="text-align:center;font-weight:bold;">{{ number_format($amount+$ot_amount,2) }}</td>            
        </tr>
    </table>
    
    
</div>

@endforeach

<?php
    function compute($key,$value,$rate,$retired){
       
        $daily_rate  = $rate;
        $hourly_rate = $rate / 8;
        switch($key){
            case 'over_time' :
                if($retired=='Y'){
                    $amount = $value * round($hourly_rate * 1.25,2);
                }else{
                    $amount = $value * round($hourly_rate * 1.0,2);
                }
                
            break;
            case 'ndays':
                $amount = $value * $daily_rate;
                
            break;

            default :
                $amount =0 ;
            break;
        }

        //return ($amount>0) ? round($amount,2) : '';
        return round($amount,2);


    }

    function compute2($key,$key2,$value,$rate,$retired){
        if($key==$key2){
            $daily_rate  = $rate;
            $hourly_rate = $rate / 8;
            switch($key){
                case 'over_time' :
                    if($retired=='Y'){
                        $amount = $value * round($hourly_rate * 1.25,2);
                    }else{
                        $amount = $value * round($hourly_rate * 1.0,2);
                    }
                    dd($key);
                break;
                case 'ndays':
                    $amount = $value * $daily_rate;
                    
                break;

                default :
                    $amount =0 ;
                break;
            }
        }else {
            $amount = 0;
        }

        //return ($amount>0) ? round($amount,2) : '';
        return round($amount,2);


    }


?>
</body>
</html>