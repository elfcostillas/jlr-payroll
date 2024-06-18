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

        $over_all_ppe_total = 0;
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
               
                $ppe_total = 0;

                $colspan = 11; 

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
                            @php $colspan++; @endphp
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
                        <td class="pr4" style="text-align:right;"> {{ $employee->daily_rate }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ $employee->ndays }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ $employee->basic_pay }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq>0) ? $employee->late_eq : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq_amount>0) ? $employee->late_eq_amount : ''; }}</td>
                       
                        @foreach($headers as $key => $val)
                            <td class="pr4"  style="text-align:right;">{{ ($employee->$key > 0) ? $employee->$key : '' }}</td>
                        @endforeach
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['earnings']>0) ? $employee->otherEarnings['earnings'] : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['retro_pay']>0) ? $employee->otherEarnings['retro_pay'] : ''; }}</td>

                        <td class="pr4"  style="text-align:right;font-weight:bold;">{{ ($employee->gross_total > 0) ? $employee->gross_total : '' }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['canteen']>0) ? $employee->otherEarnings['canteen'] : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['deductions']>0) ? $employee->otherEarnings['deductions'] : ''; }}</td>

                        <td class="pr4"  style="text-align:right;font-weight:bold;" >{{ ($employee->total_deduction>0) ? $employee->total_deduction : ''; }}</td>
                        <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:double;{{ ($employee->net_pay < ($employee->gross_total*0.3)) ? 'color:red'  : '' }};" >{{ ($employee->net_pay>0) ? $employee->net_pay :  $employee->net_pay }}</td>
                    </tr>

                    <?php

                        if(isset($summary[$employee->dept_code][$employee->job_title_name])){
                            $summary[$employee->dept_code][$employee->job_title_name] += 1;
                        }else {
                            $summary[$employee->dept_code][$employee->job_title_name] = 1;
                        }
                        
                        $ppe_total += ($employee->otherEarnings['deductions']>0) ? $employee->otherEarnings['deductions'] : 0;
                        $location_total += $employee->gross_total;
                        $location_gtotal += $employee->net_pay;
                        $location_canteen_total += ($employee->otherEarnings['canteen']>0) ? $employee->otherEarnings['canteen'] : 0; 
                    ?>

                @endforeach
                <tr>
                    <td colspan = {{ $colspan }} style="text-align:right;padding-right:4px;" > <b>SUB TOTAL </b></td>
                    <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_total > 0) ? $location_total : '' }}</td>
                    <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_canteen_total > 0) ? $location_canteen_total : '' }}</td>
                    <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($ppe_total > 0) ? $ppe_total : '' }}</td>
                    <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($ppe_total + $location_canteen_total > 0) ? $ppe_total + $location_canteen_total : '' }}</td>
                    <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_gtotal > 0) ? $location_gtotal : '' }}</td>
                   
                </tr>
            </table>

        @endif
        @php  
            $location->summary = $summary; 
            $over_all_gross_total += $location_total;
            $over_all_net_total += $location_gtotal;
            $over_all_cantenn_total += $location_canteen_total;

            $over_all_ppe_total += $ppe_total;
            
        @endphp
       
        
    @endforeach

    <table border=1  style="width:100%;border-collapse:collapse;margin-bottom:6px;" class="btable">
        <tr>
            <td colspan = {{ $colspan }} style="text-align:right;padding-right:4px;" > <b>GRAND TOTAL </b></td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_gross_total }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_cantenn_total }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_ppe_total }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_ppe_total + $over_all_cantenn_total }}</td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ $over_all_net_total }}</td>

        </tr>
    </table>

    
      
  
</body>
</html>