<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Register - Managers and Supervisors</title>

    <style>
        @page {
            margin-top: 80px;
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 20px;
        }

        .r {
            text-align: right;
        }

        .l {
            text-align: left;
        }

        .c {
            text-align: center;
        }

        .pad4 {
            padding : 0px 4px;
        }
    </style>
</head>
<body>
    <table border=0 style="width:100%;margin-bottom:2px;">
        <tr>
            <td><span style="font-size:16;" >JLR Construction and Aggregates Inc. <br>Semi Monthly Payroll  </span></td>
            <td style="font-size:12pt;vertical-align:bottom" >Payroll Period :<u style="font-size:12pt;vertical-align:bottom"> {{ $label }} </u></td>
            <td style="width:24px" ></td>
            <td style="width:26%;font-size:12pt;padding-left:24px;vertical-align:bottom" >Date / Time  Printed : {{ now()->format('m/d/Y H:i:s') }} </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    
    <?php
  
        $cols = 3 + count($data->basic_cols);

       
    ?>

    <table border=1 style="width:100%;border-collapse:collapse;font-size :10pt;">
        @foreach ($data->data as $location)
            <tr>
                <td class="pad4" colspan={{ $cols }}> {{ $location->location_altername2 }} </td>
            </tr>

            @foreach($location->divisions as $division)
                <tr>
                    <td class="pad4" colspan={{ $cols }} style="padding-left:42px"> {{ $division->div_name }}</td>
                </tr>
                @foreach ($division->departments as $department)
                    <tr>
                        <td class="pad4" colspan={{ $cols }} style="padding-left:84px"> {{ $department->dept_name }} </td>
                    </tr>
                    @php
                        $ctr = 1;
                    @endphp
                    <tr>
                        <td>No.</td>
                        <td>Name</td>
                        <td>Job Title</td>
                        @foreach ($data->basic_cols as $bcols)
                            <td>{{ $bcols->col_label }}</td>
                        @endforeach
                    </tr>
                    @foreach ($department->employees as $employee)
                        <tr>
                            <td class="pad4 r" style="width:16px">{{ $ctr++ }}</td>
                            <td class="pad4" > {{$employee->lastname}}, {{ $employee->firstname }} </td>
                            <td class="pad4" > {{$employee->job_title_name }} </td>
                            @foreach ($data->basic_cols as $bcols)
                                <td>{{ $employee->{$bcols->var_name} }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    </table>
</body>
</html>