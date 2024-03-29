@php
    use Carbon\Carbon;

    function nformat($n)
    {
        if($n!=0)
        {
            return $n;
        } else {
            return '';
        }
    }

    $total_wpay = 0;
    $total_wopay = 0;


    $total_vlwpay= 0;
    $total_slwpay= 0;

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
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><b> {{ ($leave_credits) ? $leave_credits->vacation_leave : 0 }} </b> </td>
            <td><b> {{ ($leave_credits) ? $leave_credits->sick_leave : 0 }} </b> </td>
            <td></td>
        </tr>
        @if($data!=null)
            @foreach($data as $row)
            @php
                $leave_date = Carbon::createFromFormat('Y-m-d',$row->leave_date);
                $total_vlwpay += ($row->leave_type=='VL' || $row->leave_type=='EL') ? $row->with_pay : 0;
                $total_slwpay += ($row->leave_type=='SL') ? $row->with_pay : 0;
                $total_wopay +=  $row->without_pay;
            @endphp
                <tr>
                    <td>{{ $leave_date->format('m/d/Y') }}</td>
                    <td>{{ $row->leave_type }}</td>
                    <td>{{ $row->remarks }}</td>
                    <td>{{ ($row->leave_type=='VL' || $row->leave_type=='EL') ? nformat($row->with_pay) : 0 }}</td>
                    <td>{{ ($row->leave_type=='SL') ? nformat($row->with_pay) : 0 }}</td>
                    <td>{{ nformat($row->without_pay) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan=3>Total Consumed</td>
                <td>{{ number_format($total_vlwpay,2) }}</td>
                <td>{{ number_format($total_slwpay,2) }}</td>
                <td>{{ number_format($total_wopay,2) }}</td>
            </tr>
            <?php
                
                $bal_vlwpay = $vlc - $total_vlwpay;
                $bal_slwpay = $slc - $total_slwpay;
                $bal_wopay =  0 ;
                
            ?>
            <tr>
                <td colspan=3>Balance</td>
                <td><b>{{ number_format($bal_vlwpay,2) }}</b></td>
                <td><b>{{ number_format($bal_slwpay,2) }}</b></td>
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