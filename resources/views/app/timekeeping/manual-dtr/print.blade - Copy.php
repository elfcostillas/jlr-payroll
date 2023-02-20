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
?>

<style>
    @font-face {
        font-family: arial;
        src: url({{storage_path('/fonts/Helvetica.ttf')}}) format('truetype');

    }

    * {
        font-family : "Helvetica"
    }

    table {
        font-size :9pt;
        page-break-inside: avoid; 
        border-collapse:collapse;
        margin-bottom : 4px;
    }
 
    tr { 
        page-break-inside:avoid; 
        page-break-after:auto 
    }

    .docHeader {
        text-align :center;
    }

    @page { margin: 40px 40px 60px 40px; } /* top right bottom left */

    td {
        padding : 3px;
    }
</style>

<?php
    function zformat($n)
    {   

        return ($n==0) ? '' : round($n);
    }

?>
<body>
   <div class='docHeader'> JLR Construction and Aggregates Inc. <br>
        B. Suico St. Tingub Mandaue City
   </div>
   <div class='docHeader' style="margin : 20px 0px"> DAILY TIME RECORD </div>

   <table border=0 style="border-collapse:collapse;width:100%">
    <tr>
        <td style="width:15%"><b>Employee Name</b></td>
        <td style="width:35%;text-align:left"><u> {{ $header->empname }} </u></td>
        <td style="width:15%"><b>Department</td>
        <td style="width:35%"><u>{{ $header->dept_name }}</u></td>
    </tr>
    <tr>
        <td><b>Pay Period</b></td>
        <td style="width:35%;text-align:left"><u> {{ $header->periodrange }} </u></td>
        <td><b>Position</b></td>
        <td><u>{{ $header->job_title_name}}</u></td>
    </tr>
   </table>
   <div>SHIFT SCHEDULE</div>
   <table border=1 style="border-collapse:collapse;width:100%;margin-top:20px;">
    <tr class="docHeader" >
        <td colspan=5></td>
        <td rowspan="2">REG <br> DAY</td>
        <td colspan=3>OVERTIME</td>
        <td colspan=2>Rest Day</td>
        <td colspan=2>Spec Hol</td>
        <td colspan=2>Legal Hol</td>
        <td colspan=3></td>
    </tr>
    <tr class="docHeader" >
        <td>DAY</td>
        <td>DATE</td>

        <td width="40px">IN</td>
        <td width="40px">OUT</td>
        <td>Hrs</td>
        
        <td width="40px">IN</td>
        <td width="40px">OUT</td>
        <td>Hrs</td>
        <td>Hrs</td>
        <td>OT</td>
        <td> Hrs</td>
        <td> OT</td>
        <td> Hrs</td>
        <td> OT</td>
        <td>REMARKS</td>
        <td style="width:75px;">Signature In</td>
        <td style="width:80px;">Signature Out</td>
    </tr>
        <?php
        
            $gTreg_hrs =0;
            $gTreg_day =0;
            $gTovertime_hrs =0;
            $gTrd_hrs =0;
            $gTrd_ot =0;
            $gTsh_hrs =0;
            $gTsh_ot =0;
            $gTlh_hrs =0;
            $gTlh_ot =0;
            ?>
  
    @foreach($detail as $dtr)
        <?php 
            $date = Carbon::createFromFormat('Y-m-d',$dtr->dtr_date);

            $gTreg_hrs += $dtr->reg_hrs;
            $gTreg_day += $dtr->reg_day;
            $gTovertime_hrs += $dtr->overtime_hrs;
            $gTrd_hrs += $dtr->rd_hrs;
            $gTrd_ot += $dtr->rd_ot;
            $gTsh_hrs += $dtr->sh_hrs;
            $gTsh_ot += $dtr->sh_ot;
            $gTlh_hrs += $dtr->lh_hrs;
            $gTlh_ot += $dtr->lh_ot;
        ?>
        <tr>
            <td> {{ $dtr->dayname }}</td>
            <td> {{ date_format($date,'m/d') }}</td>
            <td> {{ $dtr->time_in }} </td>
            <td> {{ $dtr->time_out }} </td>
            <td> {{ zformat($dtr->reg_hrs) }} </td>
            <td> {{ zformat($dtr->reg_day) }} </td>
            <td> {{ $dtr->overtime_in }} </td>
            <td> {{ $dtr->overtime_out }} </td>
            <td> {{ zformat($dtr->overtime_hrs) }} </td>
            <td> {{ zformat($dtr->rd_hrs) }} </td>
            <td> {{ zformat($dtr->rd_ot) }} </td>
            <td> {{ zformat($dtr->sh_hrs) }} </td>
            <td> {{ zformat($dtr->sh_ot) }} </td>
            <td> {{ zformat($dtr->lh_hrs) }} </td>
            <td> {{ zformat($dtr->lh_ot) }} </td>
            <td style="font-size : 8pt"> {{ $dtr->remarks }}</td>

            <td></td>
            <td></td>
        </tr>
    @endforeach
    <tr>
        <td>Total :</td>
        <td colspan=3></td>
      
        <td>{{ zformat($gTreg_hrs) }}</td>
        <td>{{ zformat($gTreg_day) }}</td>
        <td colspan=2></td>
        <td>{{ zformat($gTovertime_hrs) }}</td>
        <td>{{ zformat($gTrd_hrs) }}</td>
        <td>{{ zformat($gTrd_ot) }}</td>
        <td>{{ zformat($gTsh_hrs) }}</td>
        <td>{{ zformat($gTsh_ot) }}</td>
        <td>{{ zformat($gTlh_hrs) }}</td>
        <td>{{ zformat($gTlh_ot) }}</td>
        <td colspan=3></td>
    </tr>
   </table>


   <table style="margin-top:40px">
        <tr>
            <td>________________________________</td>
            <td></td>
            <td>________________________________</td>
            <td></td>
            <td>________________________________</td>
            <td></td>
        </tr>
        <tr>
            <td>Employee Signature</td>
            <td width="80px" ></td>
            <td>Immediate Supervisor Signature</td>
            <td width="80px" ></td>
            <td>Approved By</td>
            <td></td>
        </tr>
   </table>

   <div style="margin-top : 30px;font-size:9pt;">
    Note :
    <br> 
    If you render extended hours after your shift, kindly FILE OVERTIME
   </div>

</body>
</html>