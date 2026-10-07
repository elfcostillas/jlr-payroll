<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
</head>
<style>
    * {
        /* font-family: Arial, Helvetica, sans-serif; */
        font-family: 'Courier New', Courier, monospace ;
        font-size: 12px;
    }
   
    th, td {
        padding: 2px;
        text-align: left;
    }
</style>

<body>
    <h3>Q.P.I.P. - Support Group</h3>

    <table border="1" style="border-collapse: collapse; width: 100%;">
        <tr>
            <td style="width: 44px;">No.</td>
            <td style="width: 220px;">Employee Name</td>
            <td style="width: 180px;">Department</td>
            <td style=""></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td rowspan=2 style="width:160px;">Tardy</td>
            <td colspan=2 style="width:200px;">Sick Leave</td>
            <td colspan=2 style="width:200px;">Vacation Leave</td>
            <td rowspan=2 style="width:160px;">Undert Time</td>
            <td rowspan=2 style="width:160px;">Suspension</td>
            <td rowspan=2 style="width:160px;">AWOL</td>
            <td rowspan=2 style="width:160px;">Total LWOP</td>
            <td rowspan=2 style="width:160px;">Total Tardy</td>
            
        </tr>
        <tr>
          
            <td></td>
            <td></td>
            <td></td>
            <td style="width:80px;" >L WP</td>
            <td style="width:80px;" >L WOP</td>
            <td style="width:80px;" >L WP</td>
            <td style="width:80px;" >L WOP</td>

            
        </tr>
        @foreach ($data as $division)
        <tr>
            <td colspan="3">{{ $division->div_name }}</td>
        </tr>
            @foreach ($division->departments as $department)
                @foreach ($department->employees as $employee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->suffixname }}</td>
                    <td>{{ $department->dept_name }}</td>
                    <td></td>
                </tr>
                @endforeach
            @endforeach
        @endforeach
    </table>

</body>
</html>


<!-- 
+"div_name": "Quarry and Aggregates"
+"dept_name": "Aggregates"
+"division_id": "1"
+"dept_id": "1"
+"biometric_id": "703"
+"lastname": "Geraldez"
+"firstname": "Reynaldo"
+"middlename": "Manago"
+"suffixname": null
+"date_regularized": "2026-04-10"
+"exit_date": "2026-04-10"
-->