<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table>
        <tr>
            <td> JLR Construction and Aggregates Inc. </td>
        </tr>
        <tr>
            <td> 13 Month Pay - Confi </td>
        </tr>
        <tr>
            <td>{{ now()->format('m/d/Y H:i:s') }}</td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>
    <table>
        <tr>
            <td>Employee ame</td>
            @foreach ($months as $key => $mval)
                <td> {{ $mval }}  </td>
            @endforeach
            <td>Gross Pay</td>
            <td>Net Pay</td>
        </tr>

        @foreach ($semi['employees'] as $employee)
            <tr>
                <td> {{ $employee->thirteenth_month_monthly->getName() }} </td>

                @foreach ($months as $key => $mval)
                    <td> {{ $employee->thirteenth_month_monthly->getMonthly()[$key] }}  </td>
                @endforeach
                <td  class="p02 r-align"> {{ $employee->thirteenth_month_monthly->getGrossPay() }}</td>
                <td  class="p02 r-align"> {{ $employee->thirteenth_month_monthly->getNetPay() }}</td>
            </tr>
         
        @endforeach
    </table>
</body>
</html>