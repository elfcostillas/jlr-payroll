<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
       
       
    ?>
    <table border=1>
        <tr>
            <td>Employee ame</td>
            @foreach ($months as $key => $mval)
                <td> {{ $mval }}  </td>
            @endforeach
        </tr>

        @foreach ($semi['employees'] as $employee)
            <tr>
                <td> {{ $employee->thirteenth_month_monthly->getName() }} </td>

                @foreach ($months as $key => $mval)
                    <td> {{ $employee->thirteenth_month_monthly->getMonthly()[$key] }}  </td>
                @endforeach
            </tr>
         
        @endforeach
    </table>
</body>
</html>