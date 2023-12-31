<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            font-size: 8pt;
        }

        .pr4{
            text-align : right;
            padding-right : 4px;
            width: 50px;
        }
    </style>
</head>
<body>
    <?php 
        $ctr=1; 
        $colspan = 15; 

        $arr = [];
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

           

            <table border=1  style="width:100%;border-collapse:collapse;margin-bottom:6px;" class="btable">
                <tr>
                    <td colspan=16 > {{ $location->location_name }}</td>  
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
                        <th  >Total Deduction</th>
                        <th  >Net Pay</th>
                    
                    </tr>
                </thead>
                @foreach($location->employees as $employee)
                    <tr>
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

                        <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:1px solid;" >{{ ($employee->total_deduction>0) ? number_format($employee->total_deduction,2) : ''; }}</td>
                        <td class="pr4"  style="text-align:right;font-weight:bold;border-bottom:double;{{ ($employee->net_pay < ($employee->gross_total*0.3)) ? 'color:red'  : '' }};" >{{ ($employee->net_pay>0) ? number_format($employee->net_pay,2) :  number_format($employee->net_pay,2) }}</td>
                    </tr>

                    <?php
                        if(isset($summary[$employee->dept_code][$employee->job_title_name])){
                            $summary[$employee->dept_code][$employee->job_title_name] += 1;
                        }else {
                            $summary[$employee->dept_code][$employee->job_title_name] = 1;
                        }
                    ?>

                @endforeach
            </table>

        @endif
        @php  $location->summary = $summary; @endphp
       
        
    @endforeach

    <?php  $total = 0; ?>
    <table border=1 style="border-collapse:collapse; width:200;  float:left;margin-right:12px;">
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
         
            <table border=1 style="border-collapse:collapse; width:200px; float:left;margin-right:12px;">
            <tr>
                <td colspan=3 style="padding:2px;width:180px" >{{ $loc->location_name }}</td>
            </tr>
                @if($loc->employees->count()>0)
                    @foreach($loc->summary as $dept => $count)
                        @if(is_array($count))
                            @foreach($count as $key => $value)
                                <tr>
                                    <td style="width:60px" >{{ $dept }}</td>
                                    <td  > {{ $key }}</td>
                                    <td style="width:30px;padding-right:5px;text-align:right;" >{{ $value }}</td>
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