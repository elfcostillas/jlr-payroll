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
    <table id="rowClick" border=1 style="font-size : 8pt;">
        <tr>
            <td class="p02 t_header c-align" style="min-width: 164px;">Employee Name</td>

            @foreach ($data['payroll_periods'] as $period)
                <td class="p02 t_header c-align" style="min-width: 86px;">
                    {{ $period->label }}
                </td>
            @endforeach
            <td class="p02 t_header c-align" style="min-width: 86px;"> Gross Pay </td>
            <td class="p02 t_header c-align" style="min-width: 86px;"> Net Pay </td>
        </tr>

        @foreach ($data['employees'] as $employee)
            <tr>
                <th  class="p02"> {{ $employee->thirteenth_pay->getName() }}</th>
                @foreach ($data['payroll_periods'] as $iPeriod)
                
                    <td  class="p02 r-align"> 
                        {{ $employee->thirteenth_pay->getBasicPay($iPeriod->id)['value'] }}
                    </td>
                @endforeach
                <td  class="p02 r-align"> {{ $employee->thirteenth_pay->getGrossPay() }}</td>
                <td  class="p02 r-align"> {{ $employee->thirteenth_pay->getNetPay() }}</td>
            </tr>
        @endforeach
    </table>

</body>
</html>