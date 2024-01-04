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
       
      

    function nformat($n){
        if($n!=0){
            return number_format($n,2);
        }
        else {
            return '';
        }
    }
    $colspan = 15;

    $ctr = 1;
    ?>
    @foreach($data as $location)
        
        @php $summary = array();  @endphp

        @if($location->employees->count()>0)

            <?php 
                $location_total = 0;
                $location_gtotal = 0; 
            ?>

        <table style="border-collapse:collapse">
        <tr>
            <td colspan=18 > {{ $location->location_name }}</td>  
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
                        <th >Earnings</th>
                        <th >Retro Pay</th>

                        @foreach($headers as $key => $val)
                            <th >{{ $label[$key] }}</th>
                            @php $colspan++; @endphp
                        @endforeach
                        <th  >Gross Pay</th>
                        <th> PPE </th>
                        <th> Canteen </th>
                        <th  >Total Deduction</th>
                        <th  >Net Pay</th>
                    
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

                    <tr style="background-color:{{$stylee}};">
                        <td style="text-align:right;width:30px;padding-right:6px;" >{{ $ctr++ }}</td>
                        <td style="width:60px" > {{ $employee->dept_code }}</td>
                        <td style="width:90px" > {{ $employee->job_title_name }}</td>

                        <td style="text-align:left;"> {{ $employee->employee_name }} </td> 
                        <td class="pr4" style="text-align:right;"> {{ number_format($employee->daily_rate,2) }}</td>

                        <td class="pr4"  style="text-align:right;"> {{ number_format($employee->ndays,2) }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ number_format($employee->basic_pay,2) }}</td>

                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq>0) ? number_format($employee->late_eq,2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->late_eq_amount>0) ? number_format($employee->late_eq_amount,2) : ''; }}</td>

                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['earnings']>0) ? number_format($employee->otherEarnings['earnings'],2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['retro_pay']>0) ? number_format($employee->otherEarnings['retro_pay'],2) : ''; }}</td>

                        @foreach($headers as $key => $val)
                            <td class="pr4"  style="text-align:right;">{{ ($employee->$key > 0) ? number_format($employee->$key,2) : '' }}</td>
                        @endforeach
                        <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($employee->gross_total > 0) ? number_format($employee->gross_total,2) : '' }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['deductions']>0) ? number_format($employee->otherEarnings['deductions'],2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;"> {{ ($employee->otherEarnings['canteen']>0) ? number_format($employee->otherEarnings['canteen'],2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;" >{{ ($employee->total_deduction>0) ? number_format($employee->total_deduction,2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:double;{{ ($employee->net_pay < ($employee->gross_total*0.3)) ? 'color:red'  : '' }};" >{{ ($employee->net_pay>0) ? number_format($employee->net_pay,2) :  number_format($employee->net_pay,2) }}</td>
                    </tr>

                    <?php

                        if(isset($summary[$employee->dept_code][$employee->job_title_name])){
                            $summary[$employee->dept_code][$employee->job_title_name] += 1;
                        }else {
                            $summary[$employee->dept_code][$employee->job_title_name] = 1;
                        }

                        $location_total += $employee->gross_total;
                        $location_gtotal += $employee->net_pay;
                    ?>

                    @endforeach
            <tr>
            <td colspan = 13 ></td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_total > 0) ? number_format($location_total,2) : '' }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($location_gtotal > 0) ? number_format($location_gtotal,2) : '' }}</td>

            </tr>
            
        </table>
        @endif
        @php  $location->summary = $summary; @endphp
       
        
    @endforeach      

</body>
</html>