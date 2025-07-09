<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * {
            font-size: 9pt;
            font-family: 'Consolas';
        }
    </style>
</head>

<body>
    <table border=1 style="border-collapse:collapse;">
        <tr>
            <td width="90px" >Biometric ID</td>
            <td width="220px">Employee Name</td>
        </tr>

       @for($index=1;$index <= $data['range']->r2;$index++)
            <tr>
                <td> {{ $index }} </td>
                <td> {{ array_key_exists("$index",$emp) ? $emp["$index"] : '' }} </td>
            </tr>

       @endfor

    </table>
</body>
</html>