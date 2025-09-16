<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @foreach($result as $location)
        <table style="margin-bottom : 8px;" border=1 style="border-collapse:collapse;"  >
            <tr>
                <td colspan="6">  {{ $location->location_name }}</td>
            </tr>
            @foreach($location->divisions as $division)
                <tr>
                    <td style="min-width:40px"> </td>
                    <td colspan="5">({{ $division->div_code }}) - {{ $division->div_name }}</td>
                </tr>
                @foreach($division->departments as $department)
                  
                    @if(count($department->leaves)>0)
                    <tr>
                        <td style="min-width:40px"> </td>
                        <td style="min-width:40px"> </td>
                        <td colspan=4>{{ $department->dept_name }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Employee Name</td>
                        <td>Leave Type</td>
                        <td>Reason</td>
                    </tr>
                        @foreach($department->leaves as $leave)
                            <tr>
                                <td style="min-width:40px"> </td>
                                <td style="min-width:40px"> </td>
                                <td style="min-width:40px"> </td>
                                <td> {{ $leave->employee_name }}  </td>
                                <td> {{ $leave->leave_type_desc }}</td>
                                <td> {{ $leave->remarks }}</td>
                            </tr>
                        @endforeach
                    
                    @endif
                    
                @endforeach
            @endforeach
        </table>
    @endforeach
</body>
</html>