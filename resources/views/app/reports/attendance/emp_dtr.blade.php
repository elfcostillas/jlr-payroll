<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table border=1 style="border-collapse:collapse;">
        <tr>
            <td>Date</td>
            <td>Time In</td>
            <td>Time Out</td>
            <td>Day</td>
        </tr>
        @foreach ($data as $row)
            <tr>
                <td>{{ date_format(date_create($row->dtr_date),'m/d/Y') }}</td>
                <td>{{ ($row->time_in != '00:00') ? $row->time_in : ''  }}</td>
                <td>{{ ($row->time_out != '00:00') ? $row->time_in : ''  }}</td>
                <td>{{ ($row->ndays != 0) ? $row->ndays : '' }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>