<?php
    use Carbon\Carbon;

    function nformat($n){
        if($n==0)
        {
            return '';
        }else{
            return $n;
        }
    }

    $prev = '';
?>

<table border=1 >
    <tr>
        <td> BIO ID</td>
        <td> Employee Name</td>
        <td> Day Name</td>
        <td> Date </td>
        <td> Sched</td>
        <td> Time In</td>
        <td> Time Out</td>
        <td> Days	</td>
        <td> Late	</td>
        <td> Late (Hrs)	</td>
        <td> UT	</td>
        <td> ND	</td>
        <td> OT In	</td>
        <td> OT Out	</td>
        <td> Reg OT	</td>
        <td> ND OT	</td>
        <td> RD Hrs	</td>
        <td> RD OT	</td>
        <td> RD ND	</td>
        <td> RD ND OT	</td>
        <td> Hol Type	</td>
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
    </tr>
    @foreach($data as $row)
       
       <?php
            if($prev=='' ){
                $prev = $row->biometric_id;
            }else {
                if($prev!=$row->biometric_id){
        ?>
                <tr>
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td> 
                </tr>
        <?php
                $prev = $row->biometric_id;
                }
            }

        ?>
        <tr>
            <td> {{ $row->biometric_id }}</td>
            <td> {{ $row->empname }}</td>
            <td> {{ $row->day_name }}</td>
            <td> {{ Carbon::createFromFormat('Y-m-d',$row->dtr_date)->format('m/d/Y') }}</td>
            <td> {{ $row->work_sched }}</td>
            <td> {{ $row->time_in }}</td>
            <td> {{ $row->time_out }}</td>
            <td> {{ nformat($row->nday) }}</td>
            <td> {{ nformat($row->late) }}</td>
            <td> {{ nformat($row->late_eq) }}</td>
            <td> {{ nformat($row->under_time) }}</td>
            <td> {{ nformat($row->night_diff) }}</td>
            <td> {{ nformat($row->ot_in) }}</td>
            <td> {{ nformat($row->ot_out) }}</td>
            <td> {{ nformat($row->over_time) }}</td>
            <td> {{ nformat($row->night_diff_ot) }}</td>
            <td> {{ nformat($row->restday_hrs) }}</td>
            <td> {{ nformat($row->restday_ot) }}</td>
            <td> {{ nformat($row->restday_nd) }}</td>
            <td> {{ nformat($row->restday_ndot	) }}</td>
            <td> {{ $row->holiday_type }}</td>
            <td> {{ nformat($row->reghol_pay) }}</td>
            <td> {{ nformat($row->reghol_hrs) }}</td>
            <td> {{ nformat($row->reghol_ot) }}</td>
            <td> {{ nformat($row->reghol_rd) }}</td>
            <td> {{ nformat($row->reghol_rdnd) }}</td>
            <td> {{ nformat($row->reghol_rdot) }}</td>
            <td> {{ nformat($row->reghol_nd) }}</td>
            <td> {{ nformat($row->reghol_ndot) }}</td>
            <td> {{ nformat($row->reghol_rdndot) }}</td>
            <td> {{ nformat($row->sphol_pay) }}</td>
            <td> {{ nformat($row->sphol_hrs) }}</td>
            <td> {{ nformat($row->sphol_ot) }}</td>
            <td> {{ nformat($row->sphol_rd) }}</td>
            <td> {{ nformat($row->sphol_rdnd) }}</td>
            <td> {{ nformat($row->sphol_rdot) }}</td>
            <td> {{ nformat($row->sphol_nd) }}</td>
            <td> {{ nformat($row->sphol_ndot) }}</td>
            <td> {{ nformat($row->sphol_rdndot) }}</td>
            <td> {{ nformat($row->dblhol_pay) }}</td>
            <td> {{ nformat($row->dblhol_hrs) }}</td>
            <td> {{ nformat($row->dblhol_ot) }}</td>
            <td> {{ nformat($row->dblhol_rd) }}</td>
            <td> {{ nformat($row->dblhol_rdnd) }}</td>
            <td> {{ nformat($row->dblhol_rdot) }}</td>
            <td> {{ nformat($row->dblhol_nd) }}</td>
            <td> {{ nformat($row->dblhol_ndot) }}</td>
            <td> {{ nformat($row->dblhol_rdndot) }}</td>

        </tr>
    @endforeach
</table>