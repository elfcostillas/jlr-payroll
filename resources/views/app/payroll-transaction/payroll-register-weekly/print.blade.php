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

        @page {
            margin : 96px 24px 24px 24px;
        }
    </style>
</head>
<body>
    <div style="page-break-after: always;" >
    
    <?php 
        $arr = [];

        $additional = count($headers);

        $over_all_gross_total = 0;
        $over_all_net_total = 0;
        $over_all_cantenn_total = 0;

        $over_all_late_amount =0;
        $over_all_ot_pay = 0;
        $over_all_leg_hol_pay = 0;
        $over_all_other_earning = 0;
        $over_all_retro_pay =0;

        $over_all_basic_pay =0;

        $over_all_total_ded = 0;
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

                $colspan = 6; 

                $location_basic = 0;

                $location_late_amount =0;
                $location_ot_pay = 0;
                $location_leg_hol_pay = 0;
                $location_other_earning = 0;
                $location_retro_pay =0;

                $location_ppe = 0;
                $location_total_ded =0;

                foreach($headers as $key => $val)
                {
                    $location_dynamicCol[$key] = 0;
                }

            ?>

            <table border=1  style="width:100%;border-collapse:collapse;margin-bottom:6px;" class="btable">
                <tr>
                    <td colspan={{ 16 + $additional }} > {{ $location->location_name }}</td>  
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
                       
                        <th> Canteen </th>
                        <th> PPE </th>
                        <th> Total Deduction</th>
                        <th> Net Pay</th>
                    
                    </tr>
                </thead>
                @foreach($location->employees as $employee)

                    <?php
                        if($employee->retired =='Y'){
                            $stylee = '#BBC3CC;';
                        }else {
                            $stylee = 'white';
                        }
     
                    ?>
                    
                    <tr style="background-color:{{ $stylee }};">
                        <td style="text-align:right;width:25px;padding-right:6px;" >{{ $ctr++ }}</td>
                        <td style="width:72px" > {{ $employee->dept_code }}</td>
                        <td style="width:86px" > {{ $employee->job_title_name }}</td>
                        <td style="text-align:left;"> {{ $employee->employee_name }} </td> 
                        <td class="pr4" style="text-align:right;"> {{ number_format($employee->daily_rate,2) }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ number_format($employee->ndays,2) }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ number_format($employee->basic_pay,2) }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq>0) ? number_format($employee->late_eq,2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq_amount>0) ? number_format($employee->late_eq_amount,2) : ''; }}</td>
                       
                        @foreach($headers as $key => $val)
                            <td class="pr4"  style="text-align:right;">{{ ($employee->$key > 0) ? number_format($employee->$key,2) : '' }}</td>
                    
                            <?php
                                  $location_dynamicCol[$key] += $employee->$key;
                            ?>
                        @endforeach
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['earnings']>0) ? number_format($employee->otherEarnings['earnings'],2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['retro_pay']>0) ? number_format($employee->otherEarnings['retro_pay'],2) : ''; }}</td>

                        <td class="pr4"  style="text-align:right;font-weight:bold;">{{ ($employee->gross_total > 0) ? number_format($employee->gross_total,2) : '' }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['canteen']>0) ? number_format($employee->otherEarnings['canteen'],2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['deductions']>0) ? number_format($employee->otherEarnings['deductions'],2) : ''; }}</td>

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

                        $location_late_amount += ($employee->late_eq_amount>0) ? $employee->late_eq_amount : 0; 
                        // $location_ot_pay  += ($employee->late_eq_amount>0) ? $employee->late_eq_amount : 0; 
                        // $location_leg_hol_pay = 0;
                        $location_other_earning += ($employee->otherEarnings['earnings']>0) ?$employee->otherEarnings['earnings']: 0; 
                        $location_retro_pay =($employee->otherEarnings['retro_pay']>0) ? $employee->otherEarnings['retro_pay']: 0;  

                        $location_ppe += $employee->otherEarnings['deductions'];
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
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_canteen_total > 0) ? number_format($location_canteen_total,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_ppe > 0) ? number_format($location_ppe,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_total_ded > 0) ? number_format($location_total_ded,2) : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_gtotal > 0) ? number_format($location_gtotal,2) : '' }}</td>
                   
                </tr>
            </table>

            @php  
                $location->summary = $summary; 
                $over_all_gross_total += $location_total;
                $over_all_net_total += $location_gtotal;
                $over_all_cantenn_total += $location_canteen_total;

                $over_all_other_earning += $location_other_earning;
                $over_all_retro_pay += $location_retro_pay;

                $over_all_basic_pay += $location_basic;

                $over_all_total_ded += $location_total_ded;
                $over_all_ppe += $location_ppe;
                
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
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_cantenn_total,2) }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ number_format($over_all_ppe,2) }}</td>
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

        @endforeach
  
</body>
</html>