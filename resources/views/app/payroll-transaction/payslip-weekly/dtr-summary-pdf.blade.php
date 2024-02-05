<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        * {
            font-size : 10pt;
        }

        #header {
            position : fixed;
            top : -40px;
        }

        @page {
            margin-top : 60px;
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
    ?>
    <table border=1 style= "width: 100%;margin-bottom : 4px;border-collapse:collapse;" >
        <tr>
            <td style="width:60px;text-align:center;">Bio ID</td>
            <td style="width:220px;text-align:center;">Name</td>
            <td style="text-align:center;" > Date </td>
            <td style="text-align:center;" >Time In</td>
            <td style="text-align:center;" >Time Out</td>
            <td style="text-align:center;" > Day </td>
            @foreach($headers as $key => $h)
               
                <td > {{ $label[$key]}} </td>
            @endforeach
        </tr>
        <tr>
           
            <td style="text-align:right;padding-right:8px;" rowspan={{ count($emp->dtr)  }} > {{ $emp->biometric_id }} </td>
            <td style="padding-left: 8px;" rowspan={{ count($emp->dtr) }} > {{ $emp->employee_name }} </td>
            @foreach($emp->dtr as $dtr)
                
                    @php $date = Carbon::createFromFormat('Y-m-d',$dtr->dtr_date) @endphp
                    <td style="text-align:center;" >{{ $date->format('m/d/Y') }}</td>
                    <td style="text-align:center;" >{{ $dtr->time_in }}</td>
                    <td style="text-align:center;" >{{ $dtr->time_out }}</td>
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
                    
                ?>
            @endforeach
        <!-- </tr> -->
    </table>
    
    <?php $totalPerEmp = 0;  ?>
    <table  border=1 style= "margin-bottom : 16px;border-collapse:collapse;">
        <tr>
            <td style="width:60px"></td>
            <td style="width:60px"></td>
            <td style="width:60px"></td>
            <td style="width:60px"></td>
            <td style="width:60px"></td>
        </tr>
        @foreach($line[$emp->biometric_id] as $key => $value)
            <?php 
            
                $amount = compute($key,$value,$emp->basic_salary,$emp->retired);
                $totalPerEmp += $amount; 
            ?>
            <tr>
                <td></td>
                <td>{{ $key }}</td>
                <td style="text-align:right;padding-right: 6px;"> {{ ($key=='ndays') ? number_format($emp->basic_salary,2) : '' }}</td>
                <td style="text-align:right;padding-right: 6px;">{{ ($value>0) ? number_format($value,2) : '' }} </td>
                <td style="text-align:right;padding-right: 6px;">{{ ($amount>0) ? number_format($amount,2) : '' }} </td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="width:60px;text-align:right;padding-right: 6px;font-weight:bold;">{{ number_format($totalPerEmp,2) }}</td>
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


?>
</body>
</html>