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
            <td>Biometric ID</td>
            <td>Pay Type</td>
            <td>Sched In</td>
            <td>Sched Out</td>
            <td>Name</td>
            <td>Date</td>
            <td>Week Day</td>
            <td>Clock In</td>
            <td>Clock Out</td>
            <td>Late</td>
            <td>Cin/Cout</td>
            <td>Totals</td>
            <td>Tardy</td>
        </tr>
        @foreach($data as $employee)
            @foreach($employee->dtr as $logs)
              
                <tr>
                    <td>{{ $employee->biometric_id }}</td>
                    <td>{{ $employee->pay_description }}</td>
                    <td> {{ $employee->time_in }} </td>
                    <td> {{ $employee->time_out }} </td>
                    <td>{{ $employee->emp_name }}</td>
                    <td> {{ $logs->dtr_date }}</td>
                    <td> {{ $logs->day_name }}</td>
                    <td> {{ $logs->time_in }}</td>
                    <td> {{ $logs->time_out }}</td>
                    <td> {{ $logs->late }}</td>
                    <td> {{ $logs->punch->cincout }} </td>
                    <td> {{ $logs->ndays }}</td>
                    <td></td>
                </tr>
            @endforeach
        @endforeach
    </table>
</body>
</html>


