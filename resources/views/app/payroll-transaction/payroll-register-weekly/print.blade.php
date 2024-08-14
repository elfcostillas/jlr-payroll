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
            width: 48px;
        }

        .circle {
            border : 1px solid black;
            border-radius: 50%;
            padding-right : 4px;
        }

        @page {
            margin : 96px 24px 24px 24px;
        }
    </style>
</head>
<body>
    <div style="page-break-after: always;" >
    
    <?php 

        use Illuminate\Support\Facades\Auth;

        $fourfive_count = 0;
        
        $arr = [];

        $ot_summ_label[3]='30 Hours';
        $ot_summ_label[4]='40 Hours';
        $ot_summ_label[5]='50 Hours';
        $ot_summ_label[6]='60 Hours';
        $ot_summ_label[7]='70 Hours';
        $ot_summ_label[8]='80+ Hours';
        
        $otByJobtitleValue = [];
        $otDept = [];
        $otJobtitle = [];


        $otReport2 = [];


        $ot_summ_value = [
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0
        ];

        $additional = count($headers);

        $over_all_gross_total = 0;
        $over_all_net_total = 0;
        $over_all_cantenn_total = 0;

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

        foreach($headers as $key => $val)
        {
            $over_all_dynamicCol[$key] = 0;
        }
    ?>

    <table border=0 style="width:100%;margin-bottom:2px;">
        <tr>
            <td><span style="font-size:10;" >PAYROLL REGISTER <br> SUPPORT GROUP</span></td>
            <td></td>
            <td style="width:200px" >Date / Time  Printed: {{ date_format(now(),'m/d/Y H:i:s') }}</td>
        </tr>
        <tr>
            <td>Payroll Period : {{ $period_label}}</td>
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

                $location_late_amount =0;
                $location_ot_pay = 0;
                $location_leg_hol_pay = 0;
                $location_other_earning = 0;
                $location_retro_pay =0;

                $location_ppe = 0;
                $location_total_ded =0;
                $location_office_account = 0;

                foreach($headers as $key => $val)
                {
                    $location_dynamicCol[$key] = 0;
                }

               

            ?>

            <table border=1  style="width:100%;border-collapse:collapse;margin-bottom:6px;" class="btable">
                <tr>
                    <td colspan={{ 18 + $additional }} > {{ $location->location_name }}</td>  
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
                            <th >{{ $label[$key] }}</th>
                          
                        @endforeach
                        <th >Other Earnings</th>
                        <th >Retro Pay</th>
                        <th> Gross Pay</th>
                        <th>Cash Advance</th>
                        <th> Canteen </th>
                        <th> PPE </th>
                        <th> Office Acct. </th>
                        <th> Total Deduction</th>
                        <th> Net Pay</th>
                    
                    </tr>
                </thead>
                @foreach($location->employees as $employee)

                    <?php
                        $entitled = false;
                        $entitled2 = false;

                        if($employee->retired =='Y'){
                            $stylee = '#BBC3CC;';
                            $stylee = 'font-weight:bold;';

                           
                        }else {
                            $stylee = 'white';
                            $stylee = '';

                        }
     
                        $color = ($employee->ndays>=7) ? 'background-color:yellow ': '' ;
                        $circle = ($employee->ndays>=7) ? 'circle': '' ;

                        $entitled = ($employee->ndays>=7) ? true : false;

                        $entitled2 = ($employee->reg_ot >= 30) ? true : false;

                        if($entitled || $entitled2){
                            $jtCircle = 'circle';
                            $jtFill = 'yellow';
                        }else{
                            $jtCircle = '';
                            $jtFill = 'white';
                        }

                        if($employee->gross_total >= 4500 && ( $employee->retired !='Y' &&  $employee->job_title_name != 'Transit Mixer Driver' ) )
                        {
                            $fourfive = 'yellow';
                            $fourfive_count++;
                        }else{
                            $fourfive = 'white';
                        }

                       
                    ?>
                    
                    <tr style="{{ $stylee }};">
                        <td style="text-align:right;width:25px;padding-right:6px;" >{{ $ctr++ }}</td>
                        <td style="width:72px; white-space: nowrap;" >  {{ $employee->dept_code }}</td>
                        <td style="width:86px; white-space: nowrap;background-color:{{$jtFill}};" > <div class="{{$jtCircle}}"> {{ $employee->job_title_name }} </div></td>
                        <td style="text-align:left;"> {{ $employee->employee_name }} </td> 
                        <td class="" style="text-align:right;"> <div class="">{{ number_format($employee->daily_rate,2) }} </div> </td>
                        <td class="pr4" style="text-align:right;{{$color}}"> <div class="{{ $circle}}">{{ number_format($employee->ndays,2) }}</div> </td>
                        <td class="pr4"  style="text-align:right;"> {{ number_format($employee->basic_pay,2) }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq>0) ? number_format($employee->late_eq,2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq_amount>0) ? number_format($employee->late_eq_amount,2) : ''; }}</td>
                       
                        @foreach($headers as $key => $val)
                            <?php
                                $over30 = ($employee->$key >= 30 && $key == 'reg_ot' ) ? 'background-color:yellow ': '' ;
                                $over30circ = ($employee->$key >= 30 && $key == 'reg_ot' ) ? 'circle ': '' ;
                            ?> 
                            <td class="pr4"  style="text-align:right;{{ $over30 }}"> <div class="{{ $over30circ}}"> {{ ($employee->$key > 0) ? number_format($employee->$key,2) : '' }} </div></td>
                    
                            <?php
                                  $location_dynamicCol[$key] += $employee->$key;
                            ?>

                            <?php
                            if(($employee->$key >= 30 && $employee->$key <=39) && $key == 'reg_ot'){
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

                            if(($employee->$key >= 40 && $employee->$key <=49) && $key == 'reg_ot'){
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

                            if(($employee->$key >= 50 && $employee->$key <=59) && $key == 'reg_ot'){
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

                            if(($employee->$key >= 60 && $employee->$key <=69) && $key == 'reg_ot'){
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

                            if(($employee->$key >= 70 && $employee->$key <=79) && $key == 'reg_ot'){
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
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['earnings']>0) ? number_format($employee->otherEarnings['earnings'],2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['retro_pay']>0) ? number_format($employee->otherEarnings['retro_pay'],2) : ''; }}</td>

                        <td class="pr4"  style="text-align:right;font-weight:bold;background-color:{{$fourfive}};">{{ ($employee->gross_total > 0) ? number_format($employee->gross_total,2) : '' }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['cash_advance']>0) ? number_format($employee->otherEarnings['cash_advance'],2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['canteen']>0) ? number_format($employee->otherEarnings['canteen'],2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['deductions']>0) ? number_format($employee->otherEarnings['deductions'],2) : ''; }}</td>
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

                        $location_late_amount += ($employee->late_eq_amount>0) ? $employee->late_eq_amount : 0; 
                        // $location_ot_pay  += ($employee->late_eq_amount>0) ? $employee->late_eq_amount : 0; 
                        // $location_leg_hol_pay = 0;
                        $location_other_earning += ($employee->otherEarnings['earnings']>0) ?$employee->otherEarnings['earnings']: 0; 
                        $location_retro_pay =($employee->otherEarnings['retro_pay']>0) ? $employee->otherEarnings['retro_pay']: 0;  

                        $location_ppe += $employee->otherEarnings['deductions'];
                        $location_office_account+= $employee->otherEarnings['office_account'];
                        $location_total_ded +=$employee->total_deduction;
                    ?>

                @endforeach
                <tr>
                    <td colspan = {{ $colspan }} style="text-align:right;padding-right:4px;" > <b>SUB TOTAL </b></td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ ($location_basic > 0) ? number_format($location_basic,2) : ''  }}</td> <!-- BASIC -->
                    <td class="pr4"></td><!-- Late Hrs -->
                    <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_late_amount > 0) ? number_format($location_late_amount,2) : '' }}</td>
                    @foreach($headers as $key => $val)

                        @if(str_contains($key,'amount'))
                        <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ number_format($location_dynamicCol[$key],2)  }}</td>
                        @else
                            <td></td>
                        @endif

                        <?php
                            $over_all_dynamicCol[$key] += $location_dynamicCol[$key];
                        ?>
                    @endforeach
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_other_earning > 0) ? number_format($location_other_earning,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_retro_pay > 0) ? number_format($location_retro_pay,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_total > 0) ? number_format($location_total,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_cash_advance > 0) ? number_format($location_cash_advance,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_canteen_total > 0) ? number_format($location_canteen_total,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_ppe > 0) ? number_format($location_ppe,2) : '' }}</td>
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
                
            @endphp

        @endif
       
       
        
    @endforeach
    
    <table border=1  style="width:100%;border-collapse:collapse;margin-bottom:6px;" class="btable">
        <tr>
            <td style="text-align:right;padding-right:4px;" > <b>GRAND TOTAL </b></td>
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_basic_pay,2) }}</td> <!-- BASIC -->
            <td class="pr4"></td> <!-- LATE HRS -->
            <td class="pr4"></td> <!-- LATE AMT -->
          
            @foreach($headers as $key => $val)
                @if(str_contains($key,'amount'))
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ number_format($over_all_dynamicCol[$key],2)  }}</td>
                @else
                    <td class="pr4"></td>
                @endif
            
            @endforeach
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_other_earning,2) }}</td> <!-- OTHER EARN -->
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_retro_pay,2) }}</td></td> <!-- RETRO PAY -->
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_gross_total,2) }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_ca_total,2) }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_cantenn_total,2) }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_ppe,2) }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_office_account,2) }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_total_ded,2) }}</td>
            
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_net_total,2) }}</td>
            
        </tr>
    </table>

    </div>


    <?php  $total = 0; ?>
    
        <table border=1 style="page-break-inside:avoid;border-collapse:collapse; width:200;  float:left;margin-right:12px;">
            <tr>
                <td colspan=2> No of Employees</td> 

                @foreach($data as $loc)
                    <tr>
                        <td style="padding:2px;width:180px" >{{ $loc->location_name }}</td>
                        <td style="padding-right:5px;text-align:right;" >{{ $loc->employees->count() }} </td>
                    </tr>
                    {{$total += $loc->employees->count(); }}
                @endforeach
                <tr>
                    <td>TOTAL</td>
                    <td style="padding-right:5px;text-align:right;" >{{ $total }}</td>
                </tr>
            </tr>
        </table>

        @foreach($data as $loc)
            <?php
                $locTotal = 0;

             
            ?>
            @if($loc->employees->count()>0)
                <table border=1 style="page-break-inside:avoid;border-collapse:collapse; width:200px; float:left;margin-right:12px;">
                <tr>
                    <td colspan=3 style="padding:2px;width:180px" >{{ $loc->location_name }}</td>
                </tr>
                    @if($loc->employees->count()>0)
                        @foreach($loc->summary as $dept => $count)
                            @if(is_array($count))
                                @foreach($count as $key => $value)
                                    <tr>
                                        <td style="width:80px" >{{ $dept }}</td>
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

        <div style="display:block;position:relative;clear:both;margin-top:8px;">
            <table border=1 style="border-collapse: collapse;float:left">
                <tr>
                    <td style="padding:2px;"> OVERTIME SUMMARY </td>
                    <td></td>
                </tr>
                @foreach($ot_summ_label as $key => $value)
                        <tr>
                            <td style="padding:2px;"> {{ $value }} </td>
                            <td style="padding:2px;text-align:right;width:24px;"> {{ $ot_summ_value[$key] }} </td>
                        </tr>
                @endforeach
            </table>
                        
            <?php
            
                
            ?>

            <table border=1 style="border-collapse: collapse;float:left;margin-left :8px;">
                <tr>
                    <td style="padding:2px;"> Gross Pay more than 4,500 </td>
                </tr>
                <tr>
                    <td style="padding:2px;text-align:center"> {{ $fourfive_count }} </td>
      
                </tr>
               
            </table>

            @foreach($otReport2 as $table)
            
                <table border=1 style="border-collapse:collapse;float:left;margin-left:21px;;width:300px;">
                    <tr>
                        <td colspan=3  style="padding:2px;text-align:center;"> {{ $ot_summ_label[$table] }}</td>
                    </tr>
                   
                    @foreach($otByJobtitleValue[$table] as $key => $row) 

                        @foreach($row as $key2 => $value)
                        <tr>
                            <td style="padding:2px;"> {{ $key }}</td>
                            <td style="padding:2px;"> {{ $key2 }} </td>
                            <td style="padding:2px;text-align:center;"> {{ $value }} </td>
                        </tr>
                        @endforeach

                    @endforeach
                </table>
            @endforeach
        </div>

        <div style="display:block;position:relative;clear:both;">
            <table style="width:100%;margin-top:16px;" border=0>
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