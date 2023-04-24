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

    $ctr = 1;
    ?>
    <div id="" >
        <table style="border-collapse:collapse">
            <thead>
                <tr>
                        <th > No. </th>
                        <th > Bio ID</th>
                        <th >Name</th>
                        <th >Daily Rate</th>
                        <th >No of Days</th>
                        <th >Basic Pay</th>
                    
                        <th >OT</th>
                        <th >OT Amount</th>
                        <th >Rest Day</th>
                        <th >RD Amount</th>

                        <th >RD OT</th>
                        <th >RD OT Amount</th>

                        <th >SP Hol (Hrs)</th>
                        <th >SP Hol Pay</th>

                        <th >SP Hol OT</th>
                        <th >SP Hol OT Pay</th>

                        <th >Reg Hol (Hrs)</th>
                        <th >Reg Hol Pay</th>

                        <th >Reg Hol OT</th>
                        <th >Reg Hol OT Pay</th>

                        <th >Other Income</th>
                        <th >Retro Pay</th>
                        <th >Gross Pay</th>
                        <th >Deduction</th>
                        <th >Net Pay</th> 
                
                </tr>
            </thead>
            @foreach($data as $e)
                <tr>
                    <td colspan="25" style="background: grey;">{{ $e->div_name }}</td>
                </tr>
                
                @foreach($e->dept as $dept)
                <tr>
                    <td colspan="25" style="background: #e3e3e3;" >{{ $dept->dept_name }}</td>
                </tr>
                    @foreach($dept->employees as $emp)
                        <tr>
                            <td style="text-align:center;">{{ $ctr }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ $emp->biometric_id }}</td>
                            <td style="text-align:left;padding : 0px 6px;white-space:nowrap;">{{ $emp->employee_name }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->daily_rate) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->days) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->basic_pay) }}</td>
                            {{--<td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->ot) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->ot_amount) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->earnings) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->gross_pay) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->deductions) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->net_pay) }}</td> --}}

                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->ot) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->ot_amount) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->restday) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->restday_amount) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->restday_ot) }}</td>

                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->restday_ot_amount) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->sp_hrs) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->sp_amount) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->sp_ot) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->sp_ot_amount) }}</td>

                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->reghol_hrs) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->reghol_amount) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->reghol_ot) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->reghol_ot_amount) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->earnings) }}</td>

                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->retro_pay) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->gross_pay) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->deductions) }}</td>
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->net_pay) }}</td>

                        </tr>
                        <?php $ctr++; ?>
                    @endforeach
                @endforeach
            
            @endforeach
            
        </table>
          
    </div>
</body>
</html>