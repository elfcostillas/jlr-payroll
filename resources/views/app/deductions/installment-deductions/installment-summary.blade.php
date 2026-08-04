<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
        }
    </style>
</head>
<body>
    @foreach ($installment_types  as $i_type)
        <?php $total_amount = 0;  ?>
        
        <table style="border-collapse:collapse;width:100%;" border=1>
            <tr>
                <td colspan=7 style="text-align:center"><b>{{ $i_type->description }}</b></td>
            </tr>
            <tr>
                <td>Biometric ID</td>
                <td>Name</td>
                <td>Deduction Type</td>
                <td>Remarks</td>

                <td>Amount</td>
            
                <td>Balance</td>
                <td>Amount to Deduct</td>
                <td>Ending Balance</td>
            </tr>
            @foreach($i_type->installments as $installment)
                <tr>
                    <td>{{ $installment->biometric_id   }}</td>
                    <td>{{ $installment->employee_name   }}</td>
                    <td>{{ $installment->description   }}</td>
                    <td>{{ $installment->remarks   }}</td>
                    <td style="text-align:right;width:6%;" >{{ number_format($installment->total_amount,2)   }}</td>
                    <td style="text-align:right;width:6%;" >{{ number_format($installment->balance,2)   }}</td>
                    <td style="text-align:right;width:6%;" >{{ number_format($installment->ammortization,2)   }}</td>
                    <td style="text-align:right;width:6%;" >{{ number_format($installment->balance - $installment->ammortization,2)   }}</td>
                </tr>
                <?php $total_amount += $installment->ammortization;  ?>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align:right;width:6%;" ></td>
                <td style="text-align:right;width:6%;font-weight:bold;" >TOTAL</td>
                <td style="text-align:right;width:6%;font-weight:bold;" >{{ number_format($total_amount,2)   }}</td>
                <td style="text-align:right;width:6%;" ></td>
            </tr>

        </table>  
    @endforeach
</body>
</html>