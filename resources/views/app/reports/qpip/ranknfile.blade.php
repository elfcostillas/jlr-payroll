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
    <h3>Q.P.I.P. - Rank and File</h3>

    <table border="1" style="border-collapse: collapse; width: 100%;">
        <tr>
            <td style="width: 44px;">No.</td>
            <td style="width: 220px;">Employee Name</td>
            <td style="width: 180px;">Department</td>
            @foreach ($months as $month)
                <td colspan="8" style="width:160px;text-align:center;"> {{ $month }} {{ $year }}</td>
                
            @endforeach
            <td colspan="8" style="width:160px;text-align:center;"> TOTALS</td>

            <td rowspan="3" > QPIP Equivalent </td>
            <td rowspan="3" > TOTAL </td>
            <td rowspan="3" > Incentives </td>

            <td rowspan="3" > Status </td>
            <td rowspan="3" > Date Hired </td>
            <td rowspan="3" > No of Months </td>
            <td rowspan="3" > Reasons </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            @foreach ($months as $month)
                <td rowspan=2 style="width:160px;">Tardy</td>
                <td colspan=2 style="width:200px;">Sick Leave</td>
                <td colspan=2 style="width:200px;">Vacation Leave</td>
                <td rowspan=2 style="width:160px;">Undert Time</td>
                <td rowspan=2 style="width:160px;">Suspension</td>
                <td rowspan=2 style="width:160px;">AWOL</td>
           
            @endforeach
            <!--  Totals  -->
                <td rowspan=2 style="width:160px;">Tardy</td>
                <td colspan=2 style="width:200px;">Sick Leave</td>
                <td colspan=2 style="width:200px;">Vacation Leave</td>
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
            <td colspan="31">{{ $division->div_name }}</td>
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
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['sl_wop']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['ut']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['sus']) }}</td>
                            <td style="width:160px;text-align:center;">{{ nformat($employee->data[$key]['awol']) }}</td>
                        
                        @endforeach
                        
                        <td style="text-align:center;" > {{ nformat(getTotal('tardy',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('vl_wp',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('vl_wop',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('sl_wp',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('sl_wop',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('ut',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('sus',$employee->data,$months)) }}</td>
                        <td style="text-align:center;" > {{ nformat(getTotal('awol',$employee->data,$months)) }}</td>

                        <td></td>
                        <td></td>
                        <td></td>
                        
                        <td> {{ $employee->estatus_desc }}</td>
                        <td> {{ $employee->date_hired }}</td>
                        <td> {{ $employee->no_months }}</td>
                        <td></td>
                  
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