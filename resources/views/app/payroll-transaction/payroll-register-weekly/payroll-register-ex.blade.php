<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>


          
    </style>
</head>
<body>

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
        $over_all_ca_total = 0;

        foreach($headers as $key => $val)
        {
            $over_all_dynamicCol[$key] = 0;
        }
    ?>

    <table border=0 style="width:100%;margin-bottom:2px;">
        <tr>
            <td style="font-size:10;">PAYROLL REGISTER SUPPORT GROUP</td>
        </tr>
        <tr>
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
                        <th> Cash Advance </th>
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
                        <td class="pr4" style="text-align:right;"> {{ $employee->daily_rate }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ $employee->ndays }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ $employee->basic_pay }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq>0) ? $employee->late_eq : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq_amount>0) ? $employee->late_eq_amount : ''; }}</td>
                       
                        @foreach($headers as $key => $val)
                            <td class="pr4"  style="text-align:right;">{{ ($employee->$key > 0) ? number_format($employee->$key,2) : '' }}</td>
                    
                            <?php
                                  $location_dynamicCol[$key] += $employee->$key;
                            ?>
                        @endforeach
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['earnings']>0) ? $employee->otherEarnings['earnings'] : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['retro_pay']>0) ? $employee->otherEarnings['retro_pay'] : ''; }}</td>

                        <td class="pr4"  style="text-align:right;font-weight:bold;">{{ ($employee->gross_total > 0) ? $employee->gross_total : '' }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['cash_advance']>0) ? $employee->otherEarnings['cash_advance'] : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['canteen']>0) ? $employee->otherEarnings['canteen'] : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['deductions']>0) ? $employee->otherEarnings['deductions'] : ''; }}</td>

                        <td class="pr4"  style="text-align:right;font-weight:bold;" >{{ ($employee->total_deduction>0) ? $employee->total_deduction : ''; }}</td>
                        <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:double;" >{{ ($employee->net_pay>0) ? $employee->net_pay :  $employee->net_pay }}</td>
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
                        
                        $location_other_earning += ($employee->otherEarnings['earnings']>0) ?$employee->otherEarnings['earnings']: 0; 
                        $location_retro_pay =($employee->otherEarnings['retro_pay']>0) ? $employee->otherEarnings['retro_pay']: 0;  

                        $location_ppe += $employee->otherEarnings['deductions'];
                        $location_total_ded +=$employee->total_deduction;
                    ?>

                @endforeach
                <tr>
                    <td colspan = {{ $colspan }} style="text-align:right;padding-right:4px;" > <b>SUB TOTAL </b></td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ ($location_basic > 0) ? $location_basic : ''  }}</td> 
                    <td class="pr4"></td>
                    <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_late_amount > 0) ? $location_late_amount : '' }}</td>
                    @foreach($headers as $key => $val)

                        @if(str_contains($key,'amount'))
                        <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ $location_dynamicCol[$key]  }}</td>
                        @else
                            <td></td>
                        @endif

                        <?php
                            $over_all_dynamicCol[$key] += $location_dynamicCol[$key];
                        ?>
                    @endforeach
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_other_earning > 0) ? $location_other_earning : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_retro_pay > 0) ? $location_retro_pay : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_total > 0) ? $location_total : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_cash_advance > 0) ? $location_cash_advance : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_canteen_total > 0) ? $location_canteen_total : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_ppe > 0) ? $location_ppe : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_total_ded > 0) ? $location_total_ded : '' }}</td>
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_gtotal > 0) ? $location_gtotal : '' }}</td>
                   
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
            
        @endphp
       
        @endif
    @endforeach

    <table border=0  style="width:100%;border-collapse:collapse;margin-bottom:6px;" class="btable">
        <tr>
           
            <td class="pr4"></td>
            <td class="pr4"></td>
            <td class="pr4"></td>
            <td class="pr4"></td>
            <td class="pr4"></td>
            <td style="text-align:right;padding-right:4px;" > <b>GRAND TOTAL </b></td>
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_basic_pay }}</td> 
                        <td></td>
                        <td></td>
            @foreach($headers as $key => $val)
                @if(str_contains($key,'amount'))
                    <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;"> {{ $over_all_dynamicCol[$key]  }}</td>
                @else
                    <td class="pr4"></td>
                @endif
            
            @endforeach
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_other_earning }}</td> 
            <td class="pr4" style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_retro_pay }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_gross_total }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_ca_total }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_cantenn_total }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_ppe }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_total_ded }}</td>
            
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_net_total }}</td>
          
        </tr>
    </table>

    
      
  
</body>
</html>