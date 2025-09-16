<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table style="border-collapse:collapse" border=1>
        <tr>
            <td>Biometric ID</td>
            <td>Name</td>
            <td>Deduction Type</td>
            <td>Remarks</td>

            <td>Amount</td>
           
            <td>Balance</td>
            <td>Amount to Deduct</td>
        </tr>

        @foreach($installments as $installment)
            <tr>
                <td>{{ $installment->biometric_id   }}</td>
                <td>{{ $installment->employee_name   }}</td>
                <td>{{ $installment->description   }}</td>
                <td>{{ $installment->remarks   }}</td>
                <td style="text-align:right;" >{{ number_format($installment->total_amount,2)   }}</td>
                <td style="text-align:right;" >{{ number_format($installment->balance,2)   }}</td>
                <td style="text-align:right;" >{{ number_format($installment->ammortization,2)   }}</td>
            </tr>

        @endforeach
    </table>
</body>
</html>