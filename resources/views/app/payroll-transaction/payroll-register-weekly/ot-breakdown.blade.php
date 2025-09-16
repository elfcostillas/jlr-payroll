<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .p2 {
            padding : 4px 6px;
        }

        .b {
            font-weight:bold;
        }
    </style>
</head>
<body>  
    <?php
        /*
            +"location_altername": "QAD (Naga)"
            +"div_code": "QAD"
            +"dept_code": "Aggregates"
            +"ot_hrs": "254.00"
            +"ot_hrs_pay": "14752.14"
        */

        $gTotalPay = 0;
        $gTotalHrs = 0;
    ?>
    <table border=1 style="border-collapse:collapse;">
        <tr>
            <td> Location</td>
            <td>Division</td>
            <td>Department</td>
            <td style="text-align:center;">Hrs</td>
            <td style="text-align:center;">Amount</td>
        </tr>
        @foreach($data as $row)
            <tr>
                <td class="p2" style="width:110px">{{ $row->location_altername }}</td>
                <td class="p2" style="width:110px">{{ $row->div_code  }}</td>
                <td class="p2" style="width:110px">{{ $row->dept_code  }}</td>
                <td class="p2"  style="text-align:right;width:110px">{{ number_format($row->ot_hrs,2)  }}</td>
                <td class="p2"  style="text-align:right;width:110px">{{ number_format($row->ot_hrs_pay,2)  }}</td>
            </tr>

            <?php
                $gTotalPay += $row->ot_hrs_pay;
                $gTotalHrs += $row->ot_hrs;
            ?>
        @endforeach
        <tr>
            <td class="p2 b" style="width:110px" colspan=3>TOTAL</td>
            <td class="p2 b"  style="text-align:right;width:110px">{{ number_format($gTotalHrs,2)  }}</td>
            <td class="p2 b"  style="text-align:right;width:110px">{{ number_format($gTotalPay,2)  }}</td>
        </tr>
    </table>
</body>
</html>