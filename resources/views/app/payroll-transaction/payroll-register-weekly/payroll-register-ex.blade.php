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

    $rcount = 1;
    ?>
    <div id="" >
        <table style="border-collapse:collapse">
            <thead>
                <tr>
                    <th >No.</th>
                    <th > Bio ID</th>
                    <th >Name</th>
                    <th >Basic Rate</th>
                    <th >Daily Rate</th>
                    <th >Allowance (Monthly)</th>
                    <th >Allowance (Daily)</th>
                    <th >No Days</th>
                    <th >Basic Pay</th>
                    
                    <th >Daily Allowance</th>
                    <th >Semi Monthly Allowance</th>

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
            @foreach($data as $employee)
            {{-- @php dd($employee->otherEarnings); @endphp --}}
             <tr style="vertical-align: top;">
                 <th>{{ $rcount }}</th>
                 <th style="width:120px;"> {{ $employee->biometric_id }} </th> 
                 <th style="text-align:left; width : 240px;"> {{ $employee->employee_name }} </th> 
                 <td style="text-align:right;"> {{ number_format($employee->basicpay,2) }}</td>
                 <td style="text-align:right;"> {{ number_format($employee->daily_rate,2) }}</td>

                 <td style="text-align:right;"> {{ ($employee->mallowance>0) ? number_format(round($employee->mallowance/2),2) : '' }}</td>
                 <td style="text-align:right;"> {{ ($employee->dallowance>0) ? number_format($employee->dallowance,2) : '' }}</td>
                 
                 
                 <td style="text-align:right;"> {{ number_format($employee->ndays,2) }}</td>
                 <td style="text-align:right;"> {{ number_format($employee->basic_pay,2) }}</td>

                 <td style="text-align:right;"> {{ ($employee->daily_allowance>0) ? number_format($employee->daily_allowance,2) : ''; }}</td>
                 <td style="text-align:right;"> {{ ($employee->semi_monthly_allowance>0) ? number_format($employee->semi_monthly_allowance,2) : ''; }}</td>

                 <td style="text-align:right;"> {{ ($employee->late_eq>0) ? number_format($employee->late_eq,2) : ''; }}</td>
                 <td style="text-align:right;"> {{ ($employee->late_eq_amount>0) ? number_format($employee->late_eq_amount,2) : ''; }}</td>
             
                 <td style="text-align:right;"> {{ ($employee->otherEarnings['earnings']>0) ? number_format($employee->otherEarnings['earnings'],2) : ''; }}</td>
                 <td style="text-align:right;"> {{ ($employee->otherEarnings['retro_pay']>0) ? number_format($employee->otherEarnings['retro_pay'],2) : ''; }}</td>
             

                 @foreach($headers as $key => $val)
                     <td style="text-align:right;">{{ ($employee->$key > 0) ? number_format($employee->$key,2) : '' }}</td>
                 @endforeach
                     <td style="text-align:right;font-weight:bold;border-bottom:1px solid;">{{ ($employee->gross_total > 0) ? number_format($employee->gross_total,2) : '' }}</td>
                
                 <td style="text-align:right;font-weight:bold;border-bottom:1px solid;" >{{ ($employee->total_deduction>0) ? number_format($employee->total_deduction,2) : ''; }}</td>
                 <td style="text-align:right;font-weight:bold;border-bottom:double;{{ ($employee->net_pay < ($employee->gross_total*0.3)) ? 'color:red'  : '' }};" >{{ ($employee->net_pay>0) ? number_format($employee->net_pay,2) :  number_format($employee->net_pay,2) }}</td>
             </tr>
             <?php $rcount++; ?>
         @endforeach
            
        </table>
          
    </div>
</body>
</html>