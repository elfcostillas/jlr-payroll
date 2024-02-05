<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            font-size : 9pt;
        }

        @page {
            margin : 40px 20px;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td><b>Employee List By Location - Support Group</b></td>
        </tr>
    </table>
    <br>
    @foreach($data as $location)
    <?php $ctr = 0; ?>
        <table border=1 style="border-collapse:collapse;width : 180px;float:left;margin:4px;">
            <tr>
                <td style="padding-left:8px;font-weight:bold;"> {{ $location->location_name }} </td>
            </tr>
            @foreach($location->employees as $employee)
                <tr>
                    <td style="padding-left:8px;"> {{ $employee->employee_name }} </td>
                </tr>
                <?php $ctr++; ?>
            @endforeach
            <tr>
                <td style="font-weight:bold;"> Employee Count : {{ $ctr }}</td>
            </tr>
        </table>
    @endforeach
</body>
</html>