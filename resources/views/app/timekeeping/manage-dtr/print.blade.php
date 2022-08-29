<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JLR - Employee DTR </title>
</head>
<?php
    use Carbon\Carbon;
?>
<body>
    @foreach($employees as $employee)
        <table border=1 style="border-collapse:collapse;margin-bottom : 16px;width:100%;">
            <tr>
                <td colspan=15> {{ $employee->empname }}</td>
            </tr>
            <tr>
                <td>Day</td>
                <td>Date</td>
                <td>Schedule</td>
                <td>Time In</td>
                <td>Time Out</td>

                <td>Days</td>
                <td>Late</td>
                <td>Late Hrs</td>
                <td>OT</td>
                <td>UT</td>
                
                <td>ND</td>
                <td>HOL</td>
                <td></td>
                <td></td>
                <td></td>
                
            </tr>
            @foreach($employee->dtr as $log)
                <?php
					$dtrdate = Carbon::createFromFormat('Y-m-d',$log->dtr_date);	
				?>
                <tr>
                    <td>{{ $log->day_name }}</td>
                    <td> {{ date_format($dtrdate,'m/d/Y')}}</td>
                    <td> {{ $log->schedule_desc }}</td>
                    <td> {{ $log->time_in }} </td>
                    <td> {{ $log->time_out }} </td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </table>

    @endforeach
</body>
</html>