<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * {
            font-size : 9pt;
            font-family : Arial;
        }

        table tr td {
            padding : 4px;
        }
    </style>
</head>
<body>
    <table border=1 style="border-collapse:collapse;">
        <tr>
            <td>Bio ID</td>
            <td>Employee Name</td>
            @foreach($month as $mkey => $mval)
                <td style="width:70px;text-align:center;">{{ $mval }}</td>
            @endforeach
        </tr>
        @foreach($emp as $e)
            <tr>
                <td>{{ $e->biometric_id }}</td>
                <td>{{ $e->emp_name }}</td>
                @foreach($month as $mkey => $mval)
                    <td style="text-align:center;">{{ ($data[$e->biometric_id][$mkey]>0) ? $data[$e->biometric_id][$mkey] : '' }}</td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>