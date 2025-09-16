<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Man Hours (Weekly)</title>
    <style>
        * {
            font-family : 'Courier New';
            font-size : 10pt !important;
        }

        table tr td {
            padding : 2px 4px;
        }

        .l {
            text-align : left;
        }

        .r {
            text-align : right;
        }
    </style>
</head>
<?php
    function nformat($n)
    {

    }

    $ctr = 1;
?>
<body>
    <div> <h4>Man Hours Report ({{ $label }})</h4>  </div>
        <table border=1  style="border-collapse:collapse;" >
            <tr>
                <td></td>
                <td>Biometric ID</td>
                <td>Employee Name</td>
                <td>Division</td>
                <td>Department</td>
                <td>Position</td>

                <td>Overtime</td>

            </tr>
            @foreach($data as $row)
                <tr>
                    <td>{{ $ctr++ }}</td>
                    <td class="r"> {{ $row->biometric_id }}</td>
                    <td class="l"> {{ $row->employee_name }}</td>
                    <td class="l"> {{ $row->div_code }} </td>
                    <td class="l"> {{ $row->dept_code }} </td>
                    <td class="l"> {{ $row->job_title_name }} </td>
                    <td class="r"> {{ round($row->ot,0) }}</td>

                </tr>
                
            @endforeach
        </table>
</body>
</html>
