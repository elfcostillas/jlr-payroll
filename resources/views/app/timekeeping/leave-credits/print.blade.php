@php
    use Carbon\Carbon;
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
            @endphp
                <tr>
                    <td>{{ $leave_date->format('m/d/Y') }}</td>
                    <td>{{ $row->leave_type }}</td>
                    <td>{{ $row->remarks }}</td>
                    <td>{{ $row->with_pay }}</td>
                    <td>{{ $row->without_pay }}</td>
                </tr>
            @endforeach

        @else
            <tr>
                <td colspan='5' >*** NO DATA FOUND *** </td>
            </tr>
        @endif
    </table>

</body>
</html>