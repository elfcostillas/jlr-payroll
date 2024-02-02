<style>
    * {
        font-size : 10pt;
    }
</style>

<?php
    use Carbon\Carbon;

    $line = [];
   
?>

@foreach($data as $emp)
    <table border=1 style= "width: 640px;margin-bottom : 4px;border-collapse:collapse;" >
        <tr>
            <td style="width:60px">Bio ID</td>
            <td style="width:220px">Name</td>
            <td> Date </td>
            <td>Time In</td>
            <td>Time Out</td>
            <td> Day </td>
            @foreach($headers as $key => $h)
               
                <td> {{ $label[$key]}} </td>
            @endforeach
        </tr>
        <tr>
           
            <td rowspan={{ count($emp->dtr) + 1 }} > {{ $emp->biometric_id }} </td>
            <td rowspan={{ count($emp->dtr) + 1 }} > {{ $emp->employee_name }} </td>
            @foreach($emp->dtr as $dtr)
                
                    @php $date = Carbon::createFromFormat('Y-m-d',$dtr->dtr_date) @endphp
                    <td>{{ $date->format('m/d/Y') }}</td>
                    <td>{{ $dtr->time_in }}</td>
                    <td>{{ $dtr->time_out }}</td>
                    <td>{{ ($dtr->ndays>0) ? $dtr->ndays : '' }}</td>
                    @foreach($headers as $key => $h)
                        <td>{{ ($dtr->$key>0) ? $dtr->$key : '' }}</td>

                        <?php
                            if(isset($line[$emp->biometric_id][$key])){
                                $line[$emp->biometric_id][$key] +=  ($dtr->$key>0) ? $dtr->$key : 0;
                            }else {
                                $line[$emp->biometric_id][$key] = 0;
                                $line[$emp->biometric_id][$key] +=  ($dtr->$key>0) ? $dtr->$key : 0;
                            }
                            
                        ?>
                    @endforeach
                    
                </tr>
                <?php
                    if(isset($line[$emp->biometric_id]['ndays'])){
                        $line[$emp->biometric_id]['ndays'] += ($dtr->ndays>0) ? $dtr->ndays : 0;
                    }else{
                        $line[$emp->biometric_id]['ndays'] = 0;
                        $line[$emp->biometric_id]['ndays'] += ($dtr->ndays>0) ? $dtr->ndays : 0;
                    }
                    
                ?>
            @endforeach
        </tr>
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


    /*
        $this->payreg['reg_ot_amount'] = round(($this->rates['hourly_rate'] * 1.25) * $this->payreg['reg_ot'],2);
        $this->payreg['reg_nd_amount'] = round(($this->rates['hourly_rate'] * 0.1) * $this->payreg['reg_nd'],2);
        $this->payreg['reg_ndot_amount'] = round(($this->rates['hourly_rate'] * 0.1 * 1.25) * $this->payreg['reg_ndot'],2);

        /*Rest Day
        $this->payreg['rd_hrs_amount'] = round(($this->rates['hourly_rate'] * 1.3) * $this->payreg['rd_hrs'],2);
        $this->payreg['rd_ot_amount'] = round(($this->rates['hourly_rate'] * 1.3 * 1.3) * $this->payreg['rd_ot'],2);
        $this->payreg['rd_nd_amount'] = round(($this->rates['hourly_rate'] * 1.3 * 0.1) * $this->payreg['rd_nd'],2);
        $this->payreg['rd_ndot_amount'] = round(($this->rates['hourly_rate'] * 1.3 * 1.1 * 1.3) * $this->payreg['rd_ndot'],2);

        /* Legal Hours 
        $this->payreg['leghol_count_amount'] =  round($this->rates['daily_rate'] * $this->payreg['leghol_count'],2);
        $this->payreg['leghol_hrs_amount'] = round($this->rates['hourly_rate'] * $this->payreg['leghol_hrs'],2);
        $this->payreg['leghol_ot_amount'] = round($this->rates['hourly_rate'] * 2 * 1.3 * $this->payreg['leghol_ot'],2);
        $this->payreg['leghol_nd_amount'] = round($this->rates['hourly_rate'] * 2 * 0.1 * $this->payreg['leghol_nd'],2);
        $this->payreg['leghol_rd_amount'] = round($this->rates['hourly_rate'] * 1.6 * $this->payreg['leghol_rd'],2);
        $this->payreg['leghol_rdnd_amount'] = round($this->rates['hourly_rate'] * 2.6 * 0.1 * $this->payreg['leghol_rdnd'],2);
        $this->payreg['leghol_rdot_amount'] = round($this->rates['hourly_rate'] * 2.6 * 1.3 * $this->payreg['leghol_rdot'],2);
        $this->payreg['leghol_ndot_amount'] =  round($this->rates['hourly_rate'] * 2 * 1.1 * 1.3 * $this->payreg['leghol_ndot'],2);
        $this->payreg['leghol_rdndot_amount'] =  round($this->rates['hourly_rate'] * 2.6 * 1.1 * 1.3 * $this->payreg['leghol_rdndot'],2);

        /* SP Holiday 
        $this->payreg['sphol_count_amount'] = round($this->rates['daily_rate'] * $this->payreg['sphol_count'],2);
        $this->payreg['sphol_hrs_amount'] = round($this->rates['hourly_rate'] * 0.3 * $this->payreg['sphol_hrs'],2);
        $this->payreg['sphol_ot_amount'] = round($this->rates['hourly_rate'] * 1.3 * 1.3 * $this->payreg['sphol_ot'],2);
        $this->payreg['sphol_nd_amount'] = round($this->rates['hourly_rate'] * 1.3 * 0.1 * $this->payreg['sphol_nd'],2);
        $this->payreg['sphol_rd_amount'] = round($this->rates['hourly_rate'] * 0.5 * $this->payreg['sphol_rd'],2);
        $this->payreg['sphol_rdot_amount'] = round($this->rates['hourly_rate'] * 1.5 * 1.3 * $this->payreg['sphol_rdot'],2);
        $this->payreg['sphol_ndot_amount'] = round($this->rates['hourly_rate'] * $this->payreg['sphol_ndot'],2);
        $this->payreg['sphol_rdndot_amount'] = round($this->rates['hourly_rate'] * 1.5 * 0.1 * 1.3 * $this->payreg['sphol_rdndot'],2);

        $this->payreg['dblhol_count_amount'] = round($this->rates['daily_rate'] * 2 * $this->payreg['dblhol_count'],2);
        $this->payreg['dblhol_hrs_amount'] = round($this->rates['hourly_rate'] * $this->payreg['dblhol_hrs'],2);

        $this->payreg['dblhol_ot_amount'] = round($this->rates['hourly_rate'] * 3 * 1.3 * $this->payreg['dblhol_ot'],2);
        $this->payreg['dblhol_nd_amount'] = round($this->rates['hourly_rate'] * 3 * 0.1 * $this->payreg['dblhol_nd'],2);
        $this->payreg['dblhol_rd_amount'] = round($this->rates['hourly_rate'] * 3.9 * $this->payreg['dblhol_rd'],2);
        $this->payreg['dblhol_rdot_amount'] = round($this->rates['hourly_rate'] * 3.9 * 1.3 * $this->payreg['dblhol_rdot'],2);
        $this->payreg['dblhol_ndot_amount'] = round($this->rates['hourly_rate'] * 3 * 1.1 * 1.3 * $this->payreg['dblhol_ndot'],2);
        $this->payreg['dblhol_rdndot_amount'] = round($this->rates['hourly_rate'] * 3.9 * 1.1 * 1.3 * $this->payreg['dblhol_rdndot'],2);
       */
?>