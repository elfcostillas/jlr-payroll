@php
    use Carbon\Carbon;

    function nformat($n)
    {
        if($n!=0)
        {
            return round($n,2);
        } else {
            return '';
        }
    }

    $total_wpay = 0;
    $total_wopay = 0;


    $total_vlwpay= 0;
    $total_slwpay= 0;

    $total_brv = 0;

    $vlc = ($leave_credits) ? $leave_credits->vacation_leave : 0;
    $slc = ($leave_credits) ? $leave_credits->sick_leave : 0;

@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leaves</title>

    <style>
        #leave_tb tr td {
            font-size : 8pt;
            padding : 3px;
        }

        #leave_header tr td {
            font-size : 9pt;
            padding : 3px;
        }

    </style>
</head>
<body>
    <table id ="leave_header" border=0 style="width:100%">
        <tr>
            <td width="33%">Name : <u> {{ $employee->empname }} </u> </td>
            <td width="33%">Division : <u> {{ $employee->div_name }} </u>  </td>
            <td width="33%">Department : <u> {{ $employee->dept_name }} </u> </td>
        </tr>
        <tr>
            <td> {{ $employee->job_title_name }} </td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <br>
        <div style="font-size:9pt;">
            <b>Leave(s) for the period 01/01/{{$year}} - 12/31/{{$year}}</b>
        </div>
    <br>
   
    <table id="leave_tb" border=1 style="border-collapse:collapse;" width="100%">
        <tr>
            <td>DATE</td>
            <td>TYPE</td>
            <td>REASONS</td>
            <td>VL W/ PAY</td>
            <td>SL W/ PAY</td>
            <td>WITHOUT PAY</td>
            <td>Bereavement</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align:right;padding-right:8px;"><b> {{ ($leave_credits) ? $leave_credits->vacation_leave : 0 }} </b> </td>
            <td style="text-align:right;padding-right:8px;"><b> {{ ($leave_credits) ? $leave_credits->sick_leave : 0 }} </b> </td>
            <td></td>
            <td></td>
        </tr>
        @if($data!=null)
            @foreach($data as $row)

            @php
               
                $leave_date = Carbon::createFromFormat('Y-m-d',$row->leave_date);
                $total_vlwpay += ($row->leave_type=='VL' || $row->leave_type=='EL') ? $row->with_pay : 0;
                $total_slwpay += ($row->leave_type=='SL') ? $row->with_pay : 0;
                $total_wopay +=  $row->without_pay;

                $brv =  ($row->leave_type=='BRV') ? $row->with_pay + $row->without_pay : 0;
            @endphp
                <tr>
                    <td>{{ $leave_date->format('m/d/Y') }}</td>
                    <td>{{ $row->leave_type }}</td>
                    <td>{{ $row->remarks }}</td>
                    <td style="text-align:right;padding-right:8px;">{{ ($row->leave_type=='VL' || $row->leave_type=='EL') ? nformat($row->with_pay) : nformat(0) }}</td>
                    <td style="text-align:right;padding-right:8px;">{{ ($row->leave_type=='SL') ? nformat($row->with_pay) :  nformat(0) }}</td>
                    <td style="text-align:right;padding-right:8px;">{{ nformat($row->without_pay) }}</td>
                    <td style="text-align:right;padding-right:8px;">{{nformat($brv)  }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan=3>Total Consumed</td>
                <td style="text-align:right;padding-right:8px;">{{ nformat($total_vlwpay) }}</td>
                <td style="text-align:right;padding-right:8px;">{{ nformat($total_slwpay) }}</td>
                <td style="text-align:right;padding-right:8px;">{{ nformat($total_wopay) }}</td>
                <td></td>
            </tr>
            <?php
                
                $bal_vlwpay = $vlc - $total_vlwpay;
                $bal_slwpay = $slc - $total_slwpay;
                $bal_wopay =  0 ;
                
            ?>
            <tr>
                <td colspan=3>Balance</td>
                <td style="text-align:right;padding-right:8px;"><b>{{ nformat($bal_vlwpay) }}</b></td>
                <td style="text-align:right;padding-right:8px;"><b>{{ nformat($bal_slwpay) }}</b></td>
                <td></td>
                <td></td>
            </tr>


        @else
            <tr>
                <td colspan='6' style="text-align:center;">*** NO DATA FOUND *** </td>
            </tr>
        @endif
    </table>

</body>
</html>