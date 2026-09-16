<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table>
        @foreach ($result as $location )
            <tr>
                <td> {{ $location->location_name }}  </td>
                
            </tr>

            @foreach($location->divisions  as $division)
                <tr>
                    <td> {{ $division->div_name }} </td>
                </tr>

                @foreach($division->departments  as $department)
                    <tr>
                        <td> {{ $department->dept_name }} </td>
                        <td>Overtime</td>
                        <td>Rest Day</td>
                        <td>Holiday</td>
                        <td>Special Holiday</td>
                        <td>Total Hours</td>
                    </tr>
                    @foreach($department->employees  as $employee)
                        <tr>
                            <td> {{ $employee->employee_name2 }} </td>
                            <td> {{ round($employee->over_time,2) }} </td>
                            <td> {{ round($employee->restday_hrs,2) }} </td>
                            <td> {{ round($employee->reghol_hrs,2) }} </td>
                            <td> {{ round($employee->sphol_hrs,2) }} </td>
                            <td> {{ round($employee->over_time + $employee->restday_hrs + $employee->reghol_hrs + $employee->sphol_hrs,2) }}  </td>
                        </tr>
                    @endforeach

                @endforeach

            @endforeach
        @endforeach
        
    </table>
</body>
</html>
