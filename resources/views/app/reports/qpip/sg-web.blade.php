<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
</head>
<style>
    * {
        /* font-family: Arial, Helvetica, sans-serif; */
        font-family: 'Courier New', Courier, monospace ;
        font-size: 10px;
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
            @foreach ($months as $month)
                <td colspan="6" style="width:160px;text-align:center;"> {{ $month }} {{ $year }}</td>
                
            @endforeach
            <td colspan="6" style="text-align:center;"> TOTAL</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            @foreach ($months as $month)
                <td style="width:160px;">Tardy</td>
                <td style="width:200px;">S.I.L</td>
                <td style="width:200px;">Leave w/o Pay</td>
                <td style="width:160px;">U.T.</td>
                <td style="width:160px;">Susp.</td>
                <td style="width:160px;">AWOL</td>
              
            @endforeach
                <td style="width:160px;">Total Tardy</td>
                <td style="width:200px;">Total S.I.L</td>
                <td style="width:200px;">Total Leave w/o Pay</td>
                <td style="width:160px;">Total U.T.</td>
                <td style="width:160px;">Total Susp.</td>
                <td style="width:160px;">Total AWOL</td>
            
        </tr>
       
        @foreach ($data as $division)
        <tr>
            <td colspan="3">{{ $division->div_name }}</td>
        </tr>
            @foreach ($division->departments as $department)
                @foreach ($department->employees as $employee)
               
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td> <!-- {{$employee->biometric_id}} --> {{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->suffixname }}</td>
                    <td>{{ $department->dept_name }}</td>

                    

                    @foreach ($months as $key => $month)
                        <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['tardy']) }}</td>
                        <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['sil']) }}</td>
                        <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['lwop']) }}</td>
                        <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['ut']) }}</td>
                        <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['sus']) }}</td>
                        <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['awol']) }}</td>
                       
                    @endforeach

                    <td style="text-align:center;" > {{ nformat(getTotal('tardy',$employee->data,$months)) }}</td>
                    <td style="text-align:center;" > {{ nformat(getTotal('sil',$employee->data,$months)) }}</td>
                    <td style="text-align:center;" > {{ nformat(getTotal('lwop',$employee->data,$months)) }}</td>
                    <td style="text-align:center;" > {{ nformat(getTotal('ut',$employee->data,$months)) }}</td>
                    <td style="text-align:center;" > {{ nformat(getTotal('sus',$employee->data,$months)) }}</td>
                    <td style="text-align:center;" > {{ nformat(getTotal('awol',$employee->data,$months)) }}</td>
                    
                </tr>
                @endforeach
            @endforeach
        @endforeach
    </table>

</body>
</html>

<?php

    function nformat($n)
    {
        return ($n == 0) ? '' : $n;
    }

    function getTotal($key,$data,$months){
        $total = 0;
        foreach($months as $ikey => $value)
        {
            $total += $data[$ikey][$key];
        }

        return $total;
    }

?>

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