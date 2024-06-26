<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        use Carbon\Carbon;

        $date_from = Carbon::createFromFormat('Y-m-d',$label->date_from );
        $date_to = Carbon::createFromFormat('Y-m-d',$label->date_to );


        $total = array(
            'gross_total' => 0,
            'deductions' => 0,
            'canteen' => 0,
            'net_pay' => 0,
        );
    ?>
<table border=1 style="border-collapse:collapse;">
        <tr>
            <td colspan="7"> Payroll Period : {{ $date_from->format('m/d/Y')}} - {{ $date_to->format('m/d/Y')}}</td>
        </tr>
        <tr>
            <td> Location</td>
            <td>Division</td>
            <td>Department</td>
            <td style="text-align:center;">Gross Pay</td>
            <td style="text-align:center;">PPE</td>
            <td style="text-align:center;">Canteen</td>
            <td style="text-align:center;">Net Pay</td>
        </tr>
        @foreach($data as $row)
            <tr>
                <td class="p2" style="width:110px">{{ $row->location_altername }}</td>
                <td class="p2" style="width:110px">{{ $row->div_code  }}</td>
                <td class="p2" style="width:110px">{{ $row->dept_code  }}</td>
                <td class="p2"  style="text-align:right;width:110px">{{ $row->gross_total  }}</td>
                <td class="p2"  style="text-align:right;width:110px">{{ $row->deductions  }}</td>
                <td class="p2"  style="text-align:right;width:110px">{{ $row->canteen  }}</td>
                <td class="p2"  style="text-align:right;width:110px">{{ $row->net_pay }}</td>
            </tr>

            <?php
                $total['gross_total'] += $row->gross_total;
                $total['deductions'] += $row->deductions; 
                $total['canteen'] += $row->canteen;
                $total['net_pay'] += $row->net_pay;
            ?>
        @endforeach
        <tr>
            <td class="p2 b" style="width:110px" colspan=3>TOTAL</td>
            <td class="p2"  style="text-align:right;width:110px">{{ $total['gross_total']  }}</td>
            <td class="p2"  style="text-align:right;width:110px">{{ $total['deductions']  }}</td>
            <td class="p2"  style="text-align:right;width:110px">{{ $total['canteen']  }}</td>
            <td class="p2"  style="text-align:right;width:110px">{{ $total['net_pay'] }}</td>
        </tr>
    </table>
</body>
</html>