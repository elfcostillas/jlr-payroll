<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        use Carbon\Carbon;
    
    ?>
    @foreach($data as $location)
        <table>
            <tr>
                <td> {{ $location->location_name }} </td>
            </tr>
        </table>

        @foreach($location->employees as $employee)
                <table>
                    <tr>
                        <td> {{ $employee->biometric_id }} - {{ $employee->employee_name }} </td>
                    </tr>
                    <tr>
                      
                        <td>Day</td>
                        <td>Date</td>
                        <td>Time In</td>
                        <td>Time Out</td>
                        <td>Days</td>
                        <td>OT</td>
                        <td>ClockIn/ClockOut</td>
                    </tr>
                    @foreach($employee->dtr as $dtr)
                            <?php
                                $date = Carbon::createFromFormat('Y-m-d',$dtr->dtr_date);
                            ?>

                            <tr>
                            
                                <td> {{ substr($date->format('l'),0,3)  }} </td>
                                <td> {{ $date->format('m/d/Y')}}</td>
                                <td> {{ ($dtr->time_in !='00:00') ? $dtr->time_in : '' }}</td>
                                <td> {{ ($dtr->time_out !='00:00') ? $dtr->time_out : '' }}</td>
                            
                                <td> {{ ($dtr->ndays>0) ? $dtr->ndays : '' }}</td>
                                <td> {{ ($dtr->over_time>0) ? $dtr->over_time : '' }}</td>
                                <td> {{ $dtr->cincout }} </td>
                            
                            </tr>
                    @endforeach
               
                  
                </table>
        @endforeach
    @endforeach
</body>
</html>