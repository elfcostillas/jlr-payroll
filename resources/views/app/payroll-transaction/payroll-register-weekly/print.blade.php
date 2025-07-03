<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            font-size: 7pt;
        }

        .pr4{
            text-align : right;
            padding-right : 4px;
            width: 60px; /* 52*/
        }

        .pr3 {
            text-align : right;
            padding-right : 4px;
            width: 44px;
        }

        .circle {
            border : 1px solid black;
            border-radius: 50%;
            padding-right : 4px;
        }

        @page {
            margin : 72px 24px 24px 18px;
           
        }
    </style>
</head>
<body>
    <div style="page-break-after: always;" >
    
    <?php 

        use Illuminate\Support\Facades\Auth;

        // dd($period->cut_off);

        $fourfive_count = 0;
        
        $arr = [];

        // $ot_summ_label[3]='30 Hours';
        // $ot_summ_label[4]='40 Hours';
        $ot_summ_label[5]='50 Hours';
        $ot_summ_label[6]='60 Hours';
        $ot_summ_label[7]='70 Hours';
        $ot_summ_label[8]='80+ Hours';
        
        $otByJobtitleValue = [];
        $otDept = [];
        $otJobtitle = [];


        $otReport2 = [];


        $ot_summ_value = [
            // '3' => 0,
            // '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0
        ];

        $additional = count($headers);

        $over_all_gross_total = 0;
        $over_all_net_total = 0;
        $over_all_cantenn_total = 0;

        $over_all_late_hrs =0;
        $over_all_late_amount =0;

        $over_all_ot_pay = 0;
        $over_all_leg_hol_pay = 0;
        $over_all_other_earning = 0;
        $over_all_retro_pay =0;
        $over_all_ca_total =0;
        $over_all_basic_pay =0;

        $over_all_total_ded = 0;
        $over_all_office_account = 0;
        $over_all_ppe = 0;

        $over_all_sss = 0;
        $over_all_hdmf = 0;
        $over_all_phic = 0;

        foreach($headers as $key => $val)
        {
            $over_all_dynamicCol[$key] = 0;
        }

        $otherOTTotal = [];

        $departmentalTotalNet = [];
        $departmentalTotalGross = [];

        $empCountPerDept = [];  // new table holds the key id and department name
        $empCountPerDeptVal = [];

        $hide = "none";
    ?>

        <table border=0 style="width:100%;margin-bottom:2px;">
            <tr>
                <td><span style="font-size:16;" >HRD <br>  Support Group Semi Monthly Payroll  </span></td>
                <td style="font-size:12pt;vertical-align:bottom" >Payroll Period :<u style="font-size:12pt;vertical-align:bottom"> {{ $period_label}} </u></td>
                <td style="width:24px" ></td>
                <td style="width:25%;font-size:12pt;padding-left:24px;vertical-align:bottom" >Date / Time  Printed: {{ date_format(now(),'m/d/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    
    @foreach($data as $location)
        
        @php $summary = array();  @endphp

        @if($location->employees->count()>0)

            <?php 

                $ctr=1; 

                $location_total = 0;
                $location_gtotal = 0; 

                $location_canteen_total = 0;
                $location_cash_advance = 0;

                $colspan = 6; 

                $location_basic = 0;

                $location_late_hrs =0;
                $location_late_amount =0;
                $location_ot_pay = 0;
                $location_leg_hol_pay = 0;
                $location_other_earning = 0;
                $location_retro_pay =0;

                $location_ppe = 0;
                $location_total_ded =0;
                $location_office_account = 0;

                $location_sss = 0;
                $location_hdmf = 0;
                $location_phic = 0;

                foreach($headers as $key => $val)
                {
                    $location_dynamicCol[$key] = 0;
                }

            //    dd($additional);

            ?>

            <table border=1  style="width:100%;border-collapse:collapse;margin-bottom:6px;" class="btable">
                <tr>
                    <td colspan={{ 16 + $additional }} > {{ $location->location_altername2 }}</td>  
                </tr>
                <thead>
                    <tr>
                        <th >No.</th>
                        <th >Dept</th>
                        <th > Job Title </th>
                        <th >Name</th>
                        <th >Daily Rate</th>
                        <th >No Days</th>
                        <th >Basic Pay</th>
                        <th >Late (Hrs)</th>
                        <th >Late Amount</th>
                    
                        @foreach($headers as $key => $val)
                            <th>{{ $label[$key] }}</th>
                          
                        @endforeach
                        <!-- <th >Other Earnings</th> -->
                        <!-- <th >Retro Pay</th> -->
                        <th> Gross Pay</th>
                        @if ($period->cut_off==1)
                            <th> HDMF </th>
                        @else
                           <th> SSS Prem</th>
                            <th> PHIC</th>
                        @endif
                     
                     

                        <!-- <th>Cash Advance</th> -->
                        <th> Canteen </th>
                        <!-- <th> PPE </th> -->
                        <th> Office Acct. </th>
                        <th> Total Deduction</th>
                        <th> Net Pay</th>
                    
                    </tr>
                </thead>
                @foreach($location->employees as $employee)

                    <?php
                    if(!array_key_exists($employee->dept_code,$empCountPerDept))
                    {
                        $empCountPerDept[$employee->dept_code] = $employee->dept_code;

                        $empCountPerDeptVal[$employee->dept_code][1] = 0;
                        $empCountPerDeptVal[$employee->dept_code][2] = 0;
                        $empCountPerDeptVal[$employee->dept_code][3] = 0;
                    }

                    $empCountPerDeptVal[$employee->dept_code][$location->id] += 1;

                        // QAD SSDiv RMC RMD  // otherOTTotal
                    if($employee->reg_ot >= 50) // from 30
                    {
                        switch($employee->div_code)
                        {

                           
                            case 'RMC';
                                if($employee->dept_code=='QA'){
                                    if(array_key_exists($employee->dept_code,$otherOTTotal))
                                    {
                                        $otherOTTotal[$employee->dept_code] += 1;
                                    }else{
                                        $otherOTTotal[$employee->dept_code] = 1;
                                    }
                                }else{
                                    if(array_key_exists($employee->div_code,$otherOTTotal))
                                    {
                                        $otherOTTotal[$employee->div_code] += 1;
                                    }else{
                                        $otherOTTotal[$employee->div_code] = 1;
                                    }
                                }
                               
                            
                            break;

                            default :
                                if(array_key_exists($employee->div_code,$otherOTTotal))
                                {
                                    $otherOTTotal[$employee->div_code] += 1;
                                }else{
                                    $otherOTTotal[$employee->div_code] = 1;
                                }
                            break;
                        }
                    }
                    
                        $entitled = false;
                        $entitled2 = false;

                        if($employee->retired =='Y'){
                            $stylee = '#BBC3CC;';
                            $stylee = 'font-weight:bold;';

                           
                        }else {
                            $stylee = 'white';
                            $stylee = '';

                        }
     
                        $color = ($employee->ndays>=$perf) ? 'background-color:yellow ': '' ;
                        $circle = ($employee->ndays>=$perf) ? 'circle': '' ;

                        $entitled = ($employee->ndays>=$perf) ? true : false;

                        $entitled2 = ($employee->reg_ot >= 50) ? true : false;

                        if($entitled || $entitled2){
                            $jtCircle = 'circle';
                            $jtFill = 'yellow';
                        }else{
                            $jtCircle = '';
                            $jtFill = 'white';
                        }

                        if($employee->gross_total >= 9000 && ( $employee->retired !='Y' &&  $employee->job_title_name != 'Transit Mixer Driver' ) )
                        {
                            $fourfive = 'yellow';
                            $fourfive_count++;
                        }else{
                            $fourfive = 'white';
                        }

                        
                        if(!isset($departmentalTotalNet[$employee->dept_code]))
                        {
                            $departmentalTotalNet[$employee->dept_code] = 0;
                        }
                        $departmentalTotalNet[$employee->dept_code] += $employee->net_pay;

                        if(!isset($departmentalTotalGross[$employee->dept_code]))
                        {
                            $departmentalTotalGross[$employee->dept_code] = 0;
                        }

                        $departmentalTotalGross[$employee->dept_code] += $employee->gross_total;


                    ?>
                    
                    <tr style="{{ $stylee }};">
                        <td style="text-align:right;width:25px;padding-right:6px;" >{{ $ctr++ }}</td>
                        <td style="width:52px" >  {{ $employee->dept_code }}</td>
                        <td style="width:102px; background-color:{{$jtFill}};" > <div class="{{$jtCircle}}"> {{ $employee->job_title_name }} </div></td>
                        <td style="text-align:left;padding-left :4px;"> {{ $employee->employee_name }} </td> 
                        <td class="pr4" style="text-align:right;"> <div class="">{{ number_format($employee->daily_rate,2) }} </div> </td>
                        <td class="" style="width:43px;text-align:right;{{$color}};padding:0px 2px;"> <div class="{{ $circle}}">{{ round($employee->ndays,2) }}</div> </td>
                        <td class="pr4"  style="text-align:right;"> {{ number_format($employee->basic_pay,2) }}</td>
                        <td class="pr3"  style="text-align:right;"> {{ ($employee->late_eq>0) ? number_format($employee->late_eq,2) : ''; }}</td>
                        <td class="pr3"  style="text-align:right;"> {{ ($employee->late_eq_amount>0) ? number_format($employee->late_eq_amount,2) : ''; }}</td> 
                       
                        @foreach($headers as $key => $val)
                            <?php
                                $over30 = ($employee->$key >= 50 && $key == 'reg_ot' ) ? 'background-color:yellow ': '' ;
                                $over30circ = ($employee->$key >= 50 && $key == 'reg_ot' ) ? 'circle ': '' ;

                                // $over30 = ($employee->$key >= 30 && $key == 'reg_ot' ) ? '': '' ;
                                // $over30circ = ($employee->$key >= 30 && $key == 'reg_ot' ) ? '': '' ;
                            ?> 

                            @if(str_contains($key,'amount'))
                                        <td class="pr4"  style="text-align:right;{{ $over30 }}"> <div class="{{ $over30circ}}"> {{ ($employee->$key > 0) ? number_format($employee->$key,2) : '' }} </div></td>
                            @else
                                        <td class="pr4"  style="text-align:center;{{ $over30 }}"> <div class="{{ $over30circ}}"> {{ ($employee->$key > 0) ? round($employee->$key,2) : '' }} </div></td>
                            @endif

                            <?php
                                  $location_dynamicCol[$key] += $employee->$key;
                            ?>

                            <?php
                            /*
                            if(($employee->$key >= 30 && $employee->$key <40) && $key == 'reg_ot'){
                                $ot_summ_value[3] +=1;

                                if(!in_array(3,$otReport2)){
                                    array_push($otReport2,3);
                                }

                                if(!in_array($employee->dept_code,$otDept))
                                {
                                    array_push($otDept,$employee->dept_code);
                                }

                                if(!in_array($employee->job_title_name,$otJobtitle))
                                {
                                    array_push($otJobtitle,$employee->job_title_name);
                                }

                                if(isset($otByJobtitleValue[3][$employee->dept_code][$employee->job_title_name]))
                                {
                                    $otByJobtitleValue[3][$employee->dept_code][$employee->job_title_name] += 1;
                                }else{
                                    $otByJobtitleValue[3][$employee->dept_code][$employee->job_title_name] = 1;
                                }

                               
                            }

                            if(($employee->$key >= 40 && $employee->$key <50) && $key == 'reg_ot'){
                                $ot_summ_value[4] +=1;

                                if(!in_array(4,$otReport2)){
                                    array_push($otReport2,4);

                                }

                                if(!in_array($employee->dept_code,$otDept))
                                {
                                    array_push($otDept,$employee->dept_code);
                                }

                                if(!in_array($employee->job_title_name,$otJobtitle))
                                {
                                    array_push($otJobtitle,$employee->job_title_name);
                                }

                                if(isset($otByJobtitleValue[4][$employee->dept_code][$employee->job_title_name]))
                                {
                                    $otByJobtitleValue[4][$employee->dept_code][$employee->job_title_name] += 1;
                                }else{
                                    $otByJobtitleValue[4][$employee->dept_code][$employee->job_title_name] = 1;
                                }

                              
                            }

                            */

                            if(($employee->$key >= 50 && $employee->$key <60) && $key == 'reg_ot'){
                                $ot_summ_value[5] +=1;

                                if(!in_array(5,$otReport2)){
                                    array_push($otReport2,5);
                                }

                                if(!in_array($employee->dept_code,$otDept))
                                {
                                    array_push($otDept,$employee->dept_code);
                                }

                                if(!in_array($employee->job_title_name,$otJobtitle))
                                {
                                    array_push($otJobtitle,$employee->job_title_name);
                                }

                                if(isset($otByJobtitleValue[5][$employee->dept_code][$employee->job_title_name]))
                                {
                                    $otByJobtitleValue[5][$employee->dept_code][$employee->job_title_name] += 1;
                                }else{
                                    $otByJobtitleValue[5][$employee->dept_code][$employee->job_title_name] = 1;
                                }

                              
                            }

                            if(($employee->$key >= 60 && $employee->$key <70) && $key == 'reg_ot'){
                                $ot_summ_value[6] +=1;

                                if(!in_array(6,$otReport2)){
                                    array_push($otReport2,6);
                                   
                                }

                                if(!in_array($employee->dept_code,$otDept))
                                {
                                    array_push($otDept,$employee->dept_code);
                                }

                                if(!in_array($employee->job_title_name,$otJobtitle))
                                {
                                    array_push($otJobtitle,$employee->job_title_name);
                                }

                                if(isset($otByJobtitleValue[6][$employee->dept_code][$employee->job_title_name]))
                                {
                                    $otByJobtitleValue[6][$employee->dept_code][$employee->job_title_name] += 1;
                                }else{
                                    $otByJobtitleValue[6][$employee->dept_code][$employee->job_title_name] = 1;
                                }

                               
                            }

                            if(($employee->$key >= 70 && $employee->$key <80) && $key == 'reg_ot'){
                                $ot_summ_value[7] +=1;

                                if(!in_array(7,$otReport2)){
                                    array_push($otReport2,7);
                                }

                                if(!in_array($employee->dept_code,$otDept))
                                {
                                    array_push($otDept,$employee->dept_code);
                                }

                                if(!in_array($employee->job_title_name,$otJobtitle))
                                {
                                    array_push($otJobtitle,$employee->job_title_name);
                                }

                                if(isset($otByJobtitleValue[7][$employee->dept_code][$employee->job_title_name]))
                                {
                                    $otByJobtitleValue[7][$employee->dept_code][$employee->job_title_name] += 1;
                                }else{
                                    $otByJobtitleValue[7][$employee->dept_code][$employee->job_title_name] = 1;
                                }

                               
                            }

                            if(($employee->$key >= 80) && $key == 'reg_ot'){
                                $ot_summ_value[8] +=1;

                                if(!in_array(8,$otReport2)){
                                    array_push($otReport2,8);
                                }

                                if(!in_array($employee->dept_code,$otDept))
                                {
                                    array_push($otDept,$employee->dept_code);
                                }

                                if(!in_array($employee->job_title_name,$otJobtitle))
                                {
                                    array_push($otJobtitle,$employee->job_title_name);
                                }

                                if(isset($otByJobtitleValue[8][$employee->dept_code][$employee->job_title_name]))
                                {
                                    $otByJobtitleValue[8][$employee->dept_code][$employee->job_title_name] += 1;
                                }else{
                                    $otByJobtitleValue[8][$employee->dept_code][$employee->job_title_name] = 1;
                                }

                            }
                            
                            ?>
                        @endforeach
                        <!-- <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['earnings']>0) ? number_format($employee->otherEarnings['earnings'],2) : ''; }}</td> -->
                        <!-- <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['retro_pay']>0) ? number_format($employee->otherEarnings['retro_pay'],2) : ''; }}</td> -->

                        <td class="pr4"  style="text-align:right;font-weight:bold;background-color:{{$fourfive}};">{{ ($employee->gross_total > 0) ? number_format($employee->gross_total,2) : '' }}</td>
                        @if ($period->cut_off==1)
                        <td class="pr3"  style="text-align:right;"> {{ ($employee->hdmf_contri>0) ? number_format($employee->hdmf_contri,2) : ''; }}</td> 
                        @else
                        <td class="pr3"  style="text-align:right;"> {{ ($employee->sss_prem>0) ? number_format($employee->sss_prem,2) : ''; }}</td> 
                        <td class="pr3"  style="text-align:right;"> {{ ($employee->phil_prem>0) ? number_format($employee->phil_prem,2) : ''; }}</td> 
                        @endif
                        <!-- <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['cash_advance']>0) ? number_format($employee->otherEarnings['cash_advance'],2) : ''; }}</td> -->
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['canteen']>0) ? number_format($employee->otherEarnings['canteen'],2) : ''; }}</td>
                        <!-- <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['deductions']>0) ? number_format($employee->otherEarnings['deductions'],2) : ''; }}</td>-->
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['office_account']>0) ? number_format($employee->otherEarnings['office_account'],2) : ''; }}</td> 

                        <td class="pr4"  style="text-align:right;font-weight:bold;" >{{ ($employee->total_deduction>0) ? number_format($employee->total_deduction,2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:double;{{ ($employee->net_pay < ($employee->gross_total*0.3)) ? 'color:red'  : '' }};" >{{ ($employee->net_pay>0) ? number_format($employee->net_pay,2) :  number_format($employee->net_pay,2) }}</td>
                    </tr>

                    <?php

                        if(isset($summary[$employee->dept_code][$employee->job_title_name])){
                            $summary[$employee->dept_code][$employee->job_title_name] += 1;
                        }else {
                            $summary[$employee->dept_code][$employee->job_title_name] = 1;
                        }

                        $location_basic += $employee->basic_pay;

                        $location_total += $employee->gross_total;
                        $location_gtotal += $employee->net_pay;
                        $location_canteen_total += ($employee->otherEarnings['canteen']>0) ? $employee->otherEarnings['canteen'] : 0; 
                        $location_cash_advance += ($employee->otherEarnings['cash_advance']>0) ? $employee->otherEarnings['cash_advance'] : 0; 
                        $location_late_hrs += ($employee->late_eq>0) ? $employee->late_eq : 0; 
                        $location_late_amount += ($employee->late_eq_amount>0) ? $employee->late_eq_amount : 0; 
                        // $location_ot_pay  += ($employee->late_eq_amount>0) ? $employee->late_eq_amount : 0; 
                        // $location_leg_hol_pay = 0;
                        $location_other_earning += ($employee->otherEarnings['earnings']>0) ?$employee->otherEarnings['earnings']: 0; 
                        $location_retro_pay +=($employee->otherEarnings['retro_pay']>0) ? $employee->otherEarnings['retro_pay']: 0;  

                        $location_ppe += $employee->otherEarnings['deductions'];
                        $location_office_account+= $employee->otherEarnings['office_account'];
                        $location_total_ded +=$employee->total_deduction;

                        $location_hdmf += $employee->hdmf_contri;
                        $location_sss += $employee->sss_prem;
                        $location_phic += $employee->phil_prem;
                    ?>

                @endforeach
                <tr>
                    <td colspan = {{ $colspan }} style="text-align:right;padding-right:4px;" > <b>SUB TOTAL </b></td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ ($location_basic > 0) ? number_format($location_basic,2) : ''  }}</td> <!-- BASIC -->
                    <td class="pr3"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_late_hrs > 0) ? number_format($location_late_hrs,2) : '' }}</td>
                    <td class="pr3"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_late_amount > 0) ? number_format($location_late_amount,2) : '' }}</td>
                    @foreach($headers as $key => $val)

                        @if(str_contains($key,'amount'))
                        <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ number_format($location_dynamicCol[$key],2)  }}</td>
                        @else
                        <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ number_format($location_dynamicCol[$key],2)  }}</td>
                        @endif

                        <?php
                            $over_all_dynamicCol[$key] += $location_dynamicCol[$key];
                        ?>
                    @endforeach
                    
                    <!-- <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_other_earning > 0) ? number_format($location_other_earning,2) : '' }}</td> -->
                    <!-- <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_retro_pay > 0) ? number_format($location_retro_pay,2) : '' }}</td> -->
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_total > 0) ? number_format($location_total,2) : '' }}</td>
                    @if ($period->cut_off==1)
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{($location_hdmf > 0) ? number_format($location_hdmf,2) : '' }}</td>
                    @else
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{($location_sss > 0) ? number_format($location_sss,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{($location_phic > 0) ? number_format($location_phic,2) : '' }}</td>
                    @endif
                    <!-- <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_cash_advance > 0) ? number_format($location_cash_advance,2) : '' }}</td> -->
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_canteen_total > 0) ? number_format($location_canteen_total,2) : '' }}</td>
                    <!-- <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_ppe > 0) ? number_format($location_ppe,2) : '' }}</td>-->
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_office_account > 0) ? number_format($location_office_account,2) : '' }}</td> 
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_total_ded > 0) ? number_format($location_total_ded,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_gtotal > 0) ? number_format($location_gtotal,2) : '' }}</td>
                   
                </tr>
            </table>

            @php  
                $location->summary = $summary; 
                $over_all_gross_total += $location_total;
                $over_all_net_total += $location_gtotal;
                $over_all_cantenn_total += $location_canteen_total;
                $over_all_ca_total += $location_cash_advance;

                $over_all_other_earning += $location_other_earning;
                $over_all_retro_pay += $location_retro_pay;

                $over_all_basic_pay += $location_basic;

                $over_all_total_ded += $location_total_ded;
                $over_all_ppe += $location_ppe;
                $over_all_office_account += $location_office_account;

                $over_all_late_hrs += $location_late_hrs;
                $over_all_late_amount += $location_late_amount;

                $over_all_sss += $location_sss;
                $over_all_hdmf += $location_hdmf; 
                $over_all_phic += $location_phic;
                
            @endphp

        @endif
        
    @endforeach
    
    <table border=1  style="width:100%;border-collapse:collapse;margin-bottom:6px;" class="btable">
        <tr>
            <td style="text-align:right;padding-right:4px;" > <b>GRAND TOTAL </b></td>
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_basic_pay,2) }}</td> <!-- BASIC -->
            <td class="pr3" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_late_hrs,2) }}</td>
            <td class="pr3" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_late_amount,2) }}</td>
          
            @foreach($headers as $key => $val)
                @if(str_contains($key,'amount'))
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ number_format($over_all_dynamicCol[$key],2)  }}</td>
                @else
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_dynamicCol[$key],2)  }}</td>
                @endif
            
            @endforeach
            <!-- <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_other_earning,2) }}</td> OTHER EARN -->
            <!-- <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_retro_pay,2) }}</td></td> RETRO PAY -->
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_gross_total,2) }}</td>
            @if ($period->cut_off==1)
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_hdmf,2) }}</td> 
            @else
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_sss,2) }}</td> 
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_phic,2) }}</td> 
            @endif
           
          
             
            <!-- <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_ca_total,2) }}</td> -->
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_cantenn_total,2) }}</td>
            <!-- <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_ppe,2) }}</td> -->
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_office_account,2) }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_total_ded,2) }}</td>
            
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_net_total,2) }}</td>
            
        </tr>
    </table>

    </div>
        <table border=0 style="width:100%;margin-bottom:2px;">
            <tr>
                <td><span style="font-size:16;" >HRD <br>  Support Group Semi Monthly Payroll  </span></td>
                <td style="font-size:12pt;vertical-align:bottom" >Payroll Period :<u style="font-size:12pt;vertical-align:bottom"> {{ $period_label}} </u></td>
                <td style="width:24px" ></td>
                <td style="width:25%;font-size:12pt;padding-left:24px;vertical-align:bottom" >Date / Time  Printed: {{ date_format(now(),'m/d/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <table border=1 style="border-collapse:collapse;float:left;width:180px;margin-right:12px;">
            <tr>
                <td  style="padding:2px;text-align:center; min-width:120px;"> Employee Count Per Dept. </td>

                <td  style="padding:2px;text-align:center;min-width:32px;"> North </td>
                <td  style="padding:2px;text-align:center;min-width:32px;"> South </td>
                <td  style="padding:2px;text-align:center;min-width:32px;"> Agg </td>
                <td  style="padding:2px;text-align:center;min-width:32px;"> TOTAL </td>


            </tr>
            <?php 
                $bpn_total = 0;
                $bps_total = 0;
                $agg_total = 0;

                $deptTotal[] = 0;
            ?>

            @foreach($empCountPerDept as $key => $val)
            <?php
                $mask_val = (in_array($val,['Plant','Quarry'])) ? 'AGG '. $val : $val;
            ?>

                <tr>
                    <td>{{ $mask_val }}</td>
                    <td style="text-align:center;"> {{ ($empCountPerDeptVal[$val][1] > 0) ? $empCountPerDeptVal[$val][1] : '' }}</td>
                    <td style="text-align:center;"> {{ ($empCountPerDeptVal[$val][2] > 0) ? $empCountPerDeptVal[$val][2] : ''  }}</td>
                    <td style="text-align:center;"> {{ ($empCountPerDeptVal[$val][3] > 0) ? $empCountPerDeptVal[$val][3] : ''  }}</td>
                    <td style="text-align:center;"> {{ $empCountPerDeptVal[$val][1] + $empCountPerDeptVal[$val][2] + $empCountPerDeptVal[$val][3] }}</td>
                </tr>
                <?php 
                    $bpn_total +=  $empCountPerDeptVal[$val][1];
                    $bps_total +=  $empCountPerDeptVal[$val][2];
                    $agg_total +=  $empCountPerDeptVal[$val][3];
                ?>
            @endforeach
                <tr>
                    <td> TOTAL </td>
                    <td style="text-align:center;">{{  ($bpn_total > 0) ? $bpn_total : '' }}</td>
                    <td style="text-align:center;">{{  ($bps_total > 0) ? $bps_total  : '' }}</td>
                    <td style="text-align:center;">{{  ($agg_total > 0) ? $agg_total : '' }}</td>
                    <td style="text-align:center;">{{ $bpn_total + $bps_total + $agg_total }}</td>
                </tr>
        </table>

    <?php  $total = 0; ?>


        @foreach($data as $loc)
            <?php
                $locTotal = 0;

             
            ?>
            @if($loc->employees->count()>0)
                <table border=1 style="page-break-inside:avoid;border-collapse:collapse; width:200px; float:left;margin-right:12px;">
                <tr>
                    <td colspan=3 style="padding:2px;width:180px" >{{ $loc->location_altername2 }}</td>
                </tr>
                    @if($loc->employees->count()>0)
                        @foreach($loc->summary as $dept => $count)
                            @if(is_array($count))
                                @foreach($count as $key => $value)
                                    <tr>
                                    <td style="width:80px" >{{ ($loc->location_altername2 =='AGG') ? $loc->location_altername2.' '.$dept : $dept }}</td>
                                        <td style="width:80px"> {{ $key }}</td>
                                        <td style="width:40px;padding-right:5px;text-align:right;" >{{ $value }}</td>
                                        @php $locTotal += $value; @endphp
                                    </tr>
                                @endforeach
                            @endif
                            

                        @endforeach
                    @endif
                    <tr>
                        <td colspan=2 >TOTAL</td>
                        <td style="width:30px;padding-right:5px;text-align:right;" >{{ $locTotal }} </td>
                    </tr>
                </table>
            @endif
        @endforeach
        <!-- B -->
        <div style="display:block;position:relative;clear:both;margin-top:8px;">
            <table border=1 style="border-collapse: collapse;float:left">
                <tr>
                    <td style="padding:2px;"> Overtime Summary </td>
                    <td></td>
                </tr>
                @foreach($ot_summ_label as $key => $value)
                        <tr>
                            <td style="padding:2px;"> {{ $value }} </td>
                            <td style="padding:2px;text-align:right;width:24px;"> {{ $ot_summ_value[$key] }} </td>
                        </tr>
                @endforeach
            </table>
            @if($otherOTTotal)
                <table border=1 style="border-collapse: collapse;float:left;margin-left :8px;">
                    <tr >
                        <td colspan=2 style="padding:0px 4px;"> Over Time >= 50++ </td>
                    </tr>
                    @foreach($otherOTTotal as $key => $val)
                    <tr>
                        <td style="padding-left:4px;"> {{ $key }} </td>
                        <td style="text-align:center"> {{ $val }} </td>
                    </tr>
                    @endforeach 
                
                </table>
            @endif
            @foreach($otReport2 as $table)
                
                <table border=1 style="border-collapse:collapse;float:left;margin-left:12px;width:240px;">
                    <tr>
                        <td colspan=3  style="padding:2px;text-align:center;"> {{ $ot_summ_label[$table] }}</td>
                    </tr>
                
                    @foreach($otByJobtitleValue[$table] as $key => $row) 
                   

                        @foreach($row as $key2 => $value)
                        <?php
                            $mask_val2 = (in_array($key,['Plant','Quarry'])) ? 'AGG '. $key : $key;
                        ?>
                        <tr>
                            <td style="padding:2px;"> {{ $mask_val2 }}</td>
                            <td style="padding:2px;"> {{ $key2 }} </td>
                            <td style="padding:2px;text-align:center;"> {{ $value }} </td>
                        </tr>
                        @endforeach

                    @endforeach
                </table>
            @endforeach
                        
            <div style="display:block;position:relative;clear:both;">
            
              
            </div>

            <!-- departmentalTotalNet
departmentalTotalGross -->
        </div>

        <div style="display:block;position:relative;clear:both;">
            <table border=1 style="border-collapse:collapse;float:left;margin-top:8px;width:180px;">
                <tr>
                    <td colspan=2  style="padding:2px;text-align:center;">  Payroll / Gross Pay </td>
                </tr>
                <?php $totalPerDeptGross = 0;?>
                @foreach($departmentalTotalGross as $dept => $amount)
                    <?php
                        $mask_val = (in_array($dept,['Plant','Quarry'])) ? 'AGG '. $dept : $dept;
                    ?>
                    <tr>
                        <td>{{ $mask_val }}</td>
                        <td style="text-align:right;padding-right:4px;"> {{ number_format($amount,2) }}</td>
                    </tr>
                    <?php $totalPerDeptGross += $amount;?>
                @endforeach
                    <tr>
                        <td>TOTAL</td>
                        <td style="text-align:right;padding-right:4px;">{{ number_format($totalPerDeptGross,2) }}</td>
                    </tr>
            </table>

            <table border=1 style="border-collapse:collapse;float:left;margin-top:8px;width:180px;margin-left:8pt;">
                <tr>
                    <td colspan=2  style="padding:2px;text-align:left;">  Payroll / Net Pay </td>
                </tr>
                <?php $totalPerDeptNet = 0;?>
                @foreach($departmentalTotalNet as $dept => $amount)
                <?php
                    $mask_val = (in_array($dept,['Plant','Quarry'])) ? 'AGG '. $dept : $dept;
                ?>
                    <tr>
                        <td>{{ $mask_val }}</td>
                        <td style="text-align:right;padding-right:4px;"> {{ number_format($amount,2) }}</td>
                    </tr>
                    <?php 
                        $totalPerDeptNet += $amount;
                    ?>
                @endforeach
                    <tr>
                        <td>TOTAL</td>
                        <td style="text-align:right;padding-right:4px;">{{ number_format($totalPerDeptNet,2) }}</td>
                    </tr>
                

            </table>

            <table border=1 style="border-collapse: collapse;float:left;margin-left :8px;margin-top:8px;">
                <tr>
                    <td style="padding:2px;"> Gross Pay P9,000 ++ except TM Drivers </td>
                </tr>
                <tr>
                    <td style="padding:2px;text-align:center"> {{ $fourfive_count }} </td>
      
                </tr>
               
            </table>


        </div>
        <div style="display:block;position:relative;clear:both;">
            <table style="width:100%;margin-top:12px;" border=0>
                <tr>
                    <td style="width:25%;text-align:center;">Prepared By :</td>
                    <td style="width:25%;text-align:center;">Checked By :</td>
                    <td style="width:25%"></td>
                    <td style="width:25%"></td>
                </tr>
                <tr>
                    <td colspan="4" style="height:30px;"></td>
                </tr>
                <tr>
                    <td style="text-align:center;"> <u> &nbsp;&nbsp;&nbsp; {{ Auth::user()->name }} &nbsp;&nbsp;&nbsp;</u></td>
                    <td style="text-align:center;"><u>&nbsp;&nbsp;&nbsp;Herbert B.Camasura&nbsp;&nbsp;&nbsp;</u></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:center;"> HR Supervisor </td>
                    <td style="text-align:center;"> HR Manager</td>
                    <td style="text-align:center;"> </td>
                    <td style="text-align:center;"> </td>
                </tr>
            </table>
            
        </div>
</body>
</html>