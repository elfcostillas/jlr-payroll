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
                        <th style="padding : 0px 4px;min-width: 30px" > No. </th>
                        <th style="padding : 0px 4px;min-width: 60px" > Bio ID</th>
                        <th style="padding : 0px 4px; width : 240px;" >Name</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Daily Rate</th>
                        <th style="padding : 0px 4px;min-width:110px;" >No of Days</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Basic Pay</th>
                        {{-- <th style="padding : 0px 4px;min-width:110px;" >O.T.</th>
                        <th style="padding : 0px 4px;min-width:110px;" >O.T. Amount</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Other Income</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Gross Pay</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Deduction</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Net Pay</th> --}}
                        <th style="padding : 0px 4px;min-width:110px;" >OT</th>
                        <th style="padding : 0px 4px;min-width:110px;" >OT Amount</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Rest Day</th>
                        <th style="padding : 0px 4px;min-width:110px;" >RD Amount</th>

                        <th style="padding : 0px 4px;min-width:110px;" >RD OT</th>
                        <th style="padding : 0px 4px;min-width:110px;" >RD OT Amount</th>

                        <th style="padding : 0px 4px;min-width:110px;" >SP Hol (Hrs)</th>
                        <th style="padding : 0px 4px;min-width:110px;" >SP Hol Pay</th>

                        <th style="padding : 0px 4px;min-width:110px;" >SP Hol OT</th>
                        <th style="padding : 0px 4px;min-width:110px;" >SP Hol OT Pay</th>

                        <th style="padding : 0px 4px;min-width:110px;" >Reg Hol (Hrs)</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Reg Hol Pay</th>

                        <th style="padding : 0px 4px;min-width:110px;" >Reg Hol OT</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Reg Hol OT Pay</th>

                        <th style="padding : 0px 4px;min-width:110px;" >Other Income</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Retro Pay</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Gross Total</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Deduction</th>
                        <th style="padding : 0px 4px;min-width:110px;" >Net Pay</th> 
                
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
                            <td style="text-align:right;padding : 0px 6px;">{{ nformat($emp->gross_total) }}</td>
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