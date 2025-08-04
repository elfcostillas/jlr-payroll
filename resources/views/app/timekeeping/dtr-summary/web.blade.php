<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php
    function nformat($n)
    {
        return ($n>0) ? $n : '';
    }

?>
<body>  
    <table border=1 style="width:100%;font-size:10pt;"   >
        <tr>
            <td>Period ID</td>
            <td> Biometric ID </td>
            <td> Employee Name</td>
            <td> Days	</td>
            <td> Late	</td>
            
            <td> UT	</td>
            <td> ND	</td>
        
            <td> Reg OT	</td>
            <td> ND OT	</td>
            <td> RD Hrs	</td>
            <td> RD OT	</td>
            <td> RD ND	</td>
            <td> RD ND OT	</td>
            <td> Reg Hol Pay	</td>
            <td> Reg Hol Hrs	</td>
            <td> Reg Hol OT	</td>
            <td> Reg Hol RD	</td>
            <td> Reg Hol RD ND	</td>
            <td> Reg Hol RD OT	</td>
            <td> Reg Hol ND	</td>
            <td> Reg Hol ND OT	</td>
            <td> Reg Hol RD ND OT	</td>
            <td> SP Hol Pay	</td>
            <td> SP Hol Hrs	</td>
            <td> SP Hol OT	</td>
            <td> SP Hol RD	</td>
            <td> SP Hol RD ND	</td>
            <td> SP Hol RD OT	</td>
            <td> SP Hol ND	</td>
            <td> SP Hol ND OT	</td>
            <td> SP Hol RD ND OT	</td>
            <td> DBL Hol Pay	</td>
            <td> DBL Hol Hrs	</td>
            <td> DBL Hol OT	</td>
            <td> DBL Hol RD	</td>
            <td> DBL Hol RD ND	</td>
            <td> DBL Hol RD OT	</td>
            <td> DBL Hol ND	</td>
            <td> DBL Hol ND OT	</td>
            <td> DBL Hol RD ND OT</td>
            <td> DBL Sp Hol Pay	</td>
            <td> DBL Sp Hol Hrs	</td>
            <td> DBL Sp Hol OT	</td>
            <td> DBL Sp Hol RD	</td>
            <td> DBL Sp Hol RD ND	</td>
            <td> DBL Sp Hol RD OT	</td>
            <td> DBL Sp Hol ND	</td>
            <td> DBL Sp Hol ND OT	</td>
            <td> DBL Sp Hol RD ND OT</td>
            <td> AWOL</td>
        </tr>
       @foreach($employees as $div)
           <tr>
                    <td colspan=44 > {{ $div->div_name }}</td>
           </tr>
           @foreach($div->depts as $dept)
                <tr>
                    <td colspan=44 > {{ $dept->dept_name  }} </td>
                </tr>
                @foreach($dept->employees as $employee)
                   
                    <tr>
                        <td> {{ $employee->period_id }} </td>
                        <td> {{ $employee->biometric_id }} </td>
                        <td> {{ $employee->employee_name }} </td>
                        <td> {{ nformat($employee->ndays) }}</td>
                        <td> {{ nformat($employee->late_eq) }}</td>
                       
                        <td> {{ nformat($employee->under_time) }}</td>
                        <td> {{ nformat($employee->night_diff) }}</td>
                       
                        <td> {{ nformat($employee->over_time) }}</td>
                        <td> {{ nformat($employee->night_diff_ot) }}</td>
                        <td> {{ nformat($employee->restday_hrs) }}</td>
                        <td> {{ nformat($employee->restday_ot) }}</td>
                        <td> {{ nformat($employee->restday_nd) }}</td>
                        <td> {{ nformat($employee->restday_ndot	) }}</td>
                        <td> {{ nformat($employee->reghol_pay) }}</td>
                        <td> {{ nformat($employee->reghol_hrs) }}</td>
                        <td> {{ nformat($employee->reghol_ot) }}</td>
                        <td> {{ nformat($employee->reghol_rd) }}</td>
                        <td> {{ nformat($employee->reghol_rdnd) }}</td>
                        <td> {{ nformat($employee->reghol_rdot) }}</td>
                        <td> {{ nformat($employee->reghol_nd) }}</td>
                        <td> {{ nformat($employee->reghol_ndot) }}</td>
                        <td> {{ nformat($employee->reghol_rdndot) }}</td>
                        <td> {{ nformat($employee->sphol_pay) }}</td>
                        <td> {{ nformat($employee->sphol_hrs) }}</td>
                        <td> {{ nformat($employee->sphol_ot) }}</td>
                        <td> {{ nformat($employee->sphol_rd) }}</td>
                        <td> {{ nformat($employee->sphol_rdnd) }}</td>
                        <td> {{ nformat($employee->sphol_rdot) }}</td>
                        <td> {{ nformat($employee->sphol_nd) }}</td>
                        <td> {{ nformat($employee->sphol_ndot) }}</td>
                        <td> {{ nformat($employee->sphol_rdndot) }}</td>

                        <td> {{ nformat($employee->dblhol_pay) }}</td>
                        <td> {{ nformat($employee->dblhol_hrs) }}</td>
                        <td> {{ nformat($employee->dblhol_ot) }}</td>
                        <td> {{ nformat($employee->dblhol_rd) }}</td>
                        <td> {{ nformat($employee->dblhol_rdnd) }}</td>
                        <td> {{ nformat($employee->dblhol_rdot) }}</td>
                        <td> {{ nformat($employee->dblhol_nd) }}</td>
                        <td> {{ nformat($employee->dblhol_ndot) }}</td>
                        <td> {{ nformat($employee->dblhol_rdndot) }}</td>

                        <td> {{ nformat($employee->dblsphol_pay) }}</td>
                        <td> {{ nformat($employee->dblsphol_hrs) }}</td>
                        <td> {{ nformat($employee->dblsphol_ot) }}</td>
                        <td> {{ nformat($employee->dblsphol_rd) }}</td>
                        <td> {{ nformat($employee->dblsphol_rdnd) }}</td>
                        <td> {{ nformat($employee->dblsphol_rdot) }}</td>
                        <td> {{ nformat($employee->dblsphol_nd) }}</td>
                        <td> {{ nformat($employee->dblsphol_ndot) }}</td>
                        <td> {{ nformat($employee->dblsphol_rdndot) }}</td>
                        <td> {{ nformat($employee->awol) }}</td>
                    </tr>
                @endforeach
           @endforeach
       @endforeach
    </table>
</body>
</html>