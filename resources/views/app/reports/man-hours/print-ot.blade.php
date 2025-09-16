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
    $ctr = 1;
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
        page-break-inside: auto; 
        border-collapse:collapse;
        margin-bottom : 4px;
    }
 
    tr { 
        page-break-inside:auto; 
        page-break-after:auto 
    }

    td {
      
    }

    .l {
        text-align : left;
    }

    .r {
        text-align : right;
    }

    table tr td {
        padding : 2px 4px;
    }

    @page { margin: 40px 40px 40px 40px; border:1px solid green } /* top right bottom left */


</style>
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