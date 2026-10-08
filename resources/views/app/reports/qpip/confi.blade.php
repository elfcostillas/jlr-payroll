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
    <h3>Q.P.I.P. - Managers and Supervisors</h3>

    <table border="1" style="border-collapse: collapse; width: 100%;">
        <tr>
            <td style="width: 44px;">No.</td>
            <td style="width: 220px;">Employee Name</td>
            <td style="width: 180px;">Department</td>
            @foreach ($months as $month)
                <td colspan="9" style="width:160px;text-align:center;"> {{ $month }} {{ $year }}</td>
                
            @endforeach
            <td colspan="9" style="width:160px;text-align:center;"> TOTALS</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            @foreach ($months as $month)
                <td rowspan=2 style="width:160px;">Tardy</td>
                <td colspan=2 style="width:200px;">Sick Leave</td>
                <td colspan=2 style="width:200px;">Vacation Leave</td>
                <td rowspan=2 style="width:200px;">S.V.L.</td>
                <td rowspan=2 style="width:160px;">Undert Time</td>
                <td rowspan=2 style="width:160px;">Suspension</td>
                <td rowspan=2 style="width:160px;">AWOL</td>
           
            @endforeach
            <!--  Totals  -->
                <td rowspan=2 style="width:160px;">Tardy</td>
                <td colspan=2 style="width:200px;">Sick Leave</td>
                <td colspan=2 style="width:200px;">Vacation Leave</td>
                <td rowspan=2 style="width:200px;">S.V.L.</td>
                <td rowspan=2 style="width:160px;">Undert Time</td>
                <td rowspan=2 style="width:160px;">Suspension</td>
                <td rowspan=2 style="width:160px;">AWOL</td>
            
        </tr>
        <tr>
          
            <td></td>
            <td></td>
            <td></td>

            @foreach ($months as $month)
                <td style="width:80px;" >L WP</td>
                <td style="width:80px;" >L WOP</td>
                <td style="width:80px;" >L WP</td>
                <td style="width:80px;" >L WOP</td>
            @endforeach
            <!--  Totals  -->
                <td style="width:80px;" >L WP</td>
                <td style="width:80px;" >L WOP</td>
                <td style="width:80px;" >L WP</td>
                <td style="width:80px;" >L WOP</td>

            
        </tr>
        @foreach ($data as $division)
        <tr>
            <td colspan="27">{{ $division->div_name }}</td>
        </tr>
            @foreach ($division->departments as $department)
                @foreach ($department->employees as $employee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->lastname }}, {{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->suffixname }}</td>
                    <td>{{ $department->dept_name }}</td>
                        @foreach ($months as $key => $month)

                            @if (!isset($key))
                                @dd($employee->data)
                            @endif

                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['tardy']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['vl_wp']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['vl_wop']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['sl_wp']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['vl_wop']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['svl']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['ut']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['sus']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['awol']) }}</td>
                        
                        @endforeach
                        
                        <td style="text-align:center;" > {{ nformat(getTotal('tardy',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('vl_wp',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('vl_wop',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('sl_wp',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('vl_wop',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('svl',$employee->data,$months)) }}</td>
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


    /*
    vl_wop
    vl_wp
    sl_wop
    sl_wp
    ut
    sus
    awol
    */
?>