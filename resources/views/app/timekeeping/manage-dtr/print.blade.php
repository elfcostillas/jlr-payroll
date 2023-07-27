<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <title>JLR - Employee DTR </title>
</head>
<?php
    use Carbon\Carbon;
?>

<style>
    @font-face {
        font-family: Helvetica;
        src: url({{storage_path('/fonts/Helvetica.ttf')}}) format('truetype');

    }

    * {
        font-family : "Helvetica"
    }

    table {
        font-size :9pt;
        page-break-inside: avoid; 
        border-collapse:collapse;
        margin-bottom : 4px;
    }
 
    tr { 
        page-break-inside:avoid; 
        page-break-after:auto 
    }

    td {
        padding : 2px;
    }

    @page { margin: 40px 40px 60px 40px; border:1px solid green } /* top right bottom left */


</style>
<body>
    @foreach($employees as $employee)
        <table border=1 style="border-collapse:collapse;margin-bottom : 16px;width:100%;">
            <tr>
                <td colspan=15 style="font-size:12pt;font-weight:bold;"> {{ $employee->empname }}</td>
            </tr>
            <tr>
                <td style="text-align:center;font-weight:bold;">Day</td>
                <td style="text-align:center;font-weight:bold;">Date</td>
                <td style="text-align:center;font-weight:bold;">Schedule</td>
                <td style="text-align:center;font-weight:bold;">Time In</td>
                <td style="text-align:center;font-weight:bold;">Time Out</td>

                <td style="text-align:center;font-weight:bold;">Days</td>
                <td style="text-align:center;font-weight:bold;">Late</td>
                <td style="text-align:center;font-weight:bold;">Late Hrs</td>
                <td style="text-align:center;font-weight:bold;">OT</td>
                <td style="text-align:center;font-weight:bold;">UT</td>
                
                <td style="text-align:center;font-weight:bold;">ND</td>
                <td style="text-align:center;font-weight:bold;">HOL</td>
                <td></td>
                <td></td>
                <td></td>
                
            </tr>
            @foreach($employee->dtr as $log)
                <?php
					$dtrdate = Carbon::createFromFormat('Y-m-d',$log->dtr_date);	
				?>
                <tr>
                    <td style="text-align:center;" > {{ strtoupper($log->day_name) }}</td>
                    <td style="text-align:center;" > {{ date_format($dtrdate,'m/d/Y')}}</td>
                    <td style="text-align:center;"> {{ $log->schedule_desc }}</td>
                    <td style="text-align:center;"> {{ $log->time_in }} </td>
                    <td style="text-align:center;"> {{ $log->time_out }} </td>

                    <td>{{ $log->ndays }}</td>
                    <td>{{ $logs->late }}</td>
                    <td></td>
                    <td>{{ $log->over_time }}</td>
                    <td>{{ $log->under_time }}</td>
                    
                    <td></td>
                    <td>{{ $log->holiday_type }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </table>

    @endforeach
</body>
</html>