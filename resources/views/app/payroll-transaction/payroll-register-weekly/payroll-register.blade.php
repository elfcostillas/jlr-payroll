<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
            * {
                font-family: 'Consolas';
                font-size : 9pt;
            }

            thead th {
                position: -webkit-sticky; /* for Safari */
                position: sticky;
                top: 0;
                background: #e3e3e3;
                color: #000;
                
            
            }

          
    </style>
</head>
<body>
    {{-- <table>
        <tr>
            <td>Last name</td>
            <td>First Name</td>
        </tr>
        @foreach($employees as $employee)
            <tr>
                <td> {{ $employee->lastname }} </td>
                <td> {{ $employee->firstname }} </td>
            </tr>
        @endforeach
    </table> --}}
    {{-- <div id="container2" >
        <table style="width:4240px;white-space:nowrap;border-collapse:collapse;" border=1>
            <thead>
                <tr>
                    @for($x=1;$x<=50;$x++)
                        <th style="width:140px"> {{ $x }} </th>

                    @endfor
                </tr>
            </thead>
            <tbody>
                @for($y=1;$y<=100;$y++)
                <tr>
                    <th> Costillas, Elmer </th>
                    @for($x=1;$x<=49;$x++)
                        <td style="width:140px"> {{ $x }} </td>

                    @endfor
                </tr>
                @endfor
            </tbody>
        </table>
    </div> --}}
    <?php
       
       //ndays,basic_pay,late_eq,late_eq_amount,under_time,under_time_amount

    function nformat($n){
        if($n!=0){
            return number_format($n,2);
        }
        else {
            return '';
        }
    }

    ?>
    <div id="" >
        <table style="border-collapse:collapse;white-space:nowrap;" border=1 >
            <thead>
                <tr>
                        <th style="padding : 0px 4px;min-width: 30px" > No. </th>
                        <th style="padding : 0px 4px;min-width: 60px" > Bio ID</th>
                        <th style="padding : 0px 4px; width : 240px;" >Name</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Daily Rate</th>
                        <th style="padding : 0px 4px;min-width:110px;" >No of Days</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Basic Pay</th>
                        <th style="padding : 0px 4px;min-width:110px;" >O.T.</th>
                        <th style="padding : 0px 4px;min-width:110px;" >O.T. Amount</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Other Income</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Gross Pay</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Deduction</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Net Pay</th>
                
                </tr>
            </thead>
            <tbody> 
                <?php $ctr = 1; ?>
                @foreach($data as $e)
                    <tr>
                        <td style="text-align:center;">{{ $ctr }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ $e->biometric_id }}</td>
                        <td style="text-align:left;padding : 0px 6px;">{{ $e->employee_name }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ nformat($e->daily_rate) }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ nformat($e->days) }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ nformat($e->basic_pay) }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ nformat($e->ot) }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ nformat($e->ot_amount) }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ nformat($e->earnings) }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ nformat($e->gross_pay) }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ nformat($e->deductions) }}</td>
                        <td style="text-align:right;padding : 0px 6px;">{{ nformat($e->net_pay) }}</td>
                    </tr>
                    <?php $ctr++; ?>
                @endforeach
            </tbody>
                  
            
        </table>
        {{--
            
            biometric_id
period_id
daily_rate
days
ot
ot_amount
basic_pay
earnings
gross_pay
deductions
net_pay@if(count($no_pay)>0)


        <table border="1" style="border-collapse:collapse;margin-top : 12px;">
            <tr>
                <td colspan="5"> Employees not in computation</td>
            </tr>
            <tr>
                <td>Biometric ID</td>
                <td>Employee Name</td>
                <td>Division</td>
                <td>Department</td>
            </tr>

            @foreach($no_pay as $e)
                <tr>
                    <td> {{ $e->biometric_id }}</td>
                    <td> {{ $e->employee_name }}</td>
                    <td> {{ $e->div_code }}</td>
                    <td> {{ $e->dept_code }}</td>
                    <td> {{ $e->job_title_name }}</td>
                    
                </tr>
            @endforeach
        </table>
        @endif --}}
    </div>
</body>
</html>