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
            color:white;
            font-size : 8pt;
            font-family : Arial;
            padding : 3px;
        }
    </style>
</head>
<body>

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
            <td><b> {{ $leave_credits->vacation_leave }} </b> </td>
            <td><b> {{ $leave_credits->sick_leave }} </b> </td>
            <td></td>
        </tr>
        @if($data!=null)
            @foreach($data as $row)
            @php
                $leave_date = Carbon::createFromFormat('Y-m-d',$row->leave_date);
                $total_vlwpay += ($row->leave_type=='VL') ? $row->with_pay : 0;
                $total_slwpay += ($row->leave_type=='SL') ? $row->with_pay : 0;
                $total_wopay +=  $row->without_pay;
            @endphp
                <tr>
                    <td>{{ $leave_date->format('m/d/Y') }}</td>
                    <td>{{ $row->leave_type }}</td>
                    <td>{{ $row->remarks }}</td>
                    <td>{{ ($row->leave_type=='VL') ? nformat($row->with_pay) : 0 }}</td>
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
          
                $bal_vlwpay = $leave_credits->vacation_leave - $total_vlwpay;
                $bal_slwpay = $leave_credits->sick_leave - $total_slwpay;
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
                <td colspan='5' style="text-align:center;">*** NO DATA FOUND *** </td>
            </tr>
        @endif
    </table>

</body>
</html>