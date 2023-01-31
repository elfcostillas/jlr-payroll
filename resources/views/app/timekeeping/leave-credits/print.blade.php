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
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leaves</title>

    <style>
        *{
            font-size : 9pt;
        }

        table tr td {
            padding : 3px;
        }
    </style>
</head>
<body>

    <table border=1 style="border-collapse:collapse;" width="100%">
        <tr>
            <td>DATE</td>
            <td>TYPE</td>
            <td>REASONS</td>
            <td>WITH PAY</td>
            <td>WITHOUT PAY</td>
        </tr>
        @if($data!=null)
            @foreach($data as $row)
            @php
                $leave_date = Carbon::createFromFormat('Y-m-d',$row->leave_date);
                $total_wpay += $row->with_pay;
                $total_wopay +=  $row->without_pay;
            @endphp
                <tr>
                    <td>{{ $leave_date->format('m/d/Y') }}</td>
                    <td>{{ $row->leave_type }}</td>
                    <td>{{ $row->remarks }}</td>
                    <td>{{ nformat($row->with_pay) }}</td>
                    <td>{{ nformat($row->without_pay) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan=3></td>
                <td>{{ number_format($total_wpay,2) }}</td>
                <td>{{ number_format($total_wopay,2) }}</td>
            </tr>

        @else
            <tr>
                <td colspan='5' style="text-align:center;">*** NO DATA FOUND *** </td>
            </tr>
        @endif
    </table>

</body>
</html>