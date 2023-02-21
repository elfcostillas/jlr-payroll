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
        font-size :8pt;
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
         font-size :8pt;
    }
</style>

<?php
    function zformat($n)
    {   

        return ($n==0) ? '' : round($n);
    }

    $gTreg_day =0;
    $gTovertime_hrs =0;

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
        <td width="45px" rowspan=2>DATE</td>
        <td width="45px" rowspan=2>DAYS</td>
        <td colspan=2 >MORNING</td>
        <td colspan=2 >AFTERNOON</td>
        <td colspan=2 >OVERTIME</td>
        <td colspan=2 >OVERTIME</td>
        <td width="40px" rowspan=2>REG <br> DAY</td>
        <td width="40px" rowspan=2>ROT</td>
        <td width="40px" rowspan=2>Rest <br> Day <br> Duty</td>
        <td width="40px" rowspan=2>Rest <br> Day <br> OT</td>
        <td rowspan=2>REMARKS</td>
        <td width="60px" rowspan=2>SIGNATURE <br> (IN) </td>
        <td width="60px" rowspan=2>SIGNATURE <br> (OUT) </td>
    </tr>
    <tr>
        <td width="45px" style="text-align:center;" >In</td>
        <td width="45px" style="text-align:center;" >Out</td>
        <td width="45px" style="text-align:center;" >In</td>
        <td width="45px" style="text-align:center;" >Out</td>
        <td width="45px" style="text-align:center;" >In</td>
        <td width="45px" style="text-align:center;" >Out</td>
        <td width="45px" style="text-align:center;" >In</td>
        <td width="45px" style="text-align:center;" >Out</td>
    </tr>
    @foreach($detail as $dtr)

    <?php 
            $date = Carbon::createFromFormat('Y-m-d',$dtr->dtr_date);
            $gTreg_day += $dtr->reg_day;
            $gTovertime_hrs += $dtr->overtime_hrs;
    ?>
   
    <tr>
        <td style="text-align:center;" >{{ $dtr->dayname }}</td>
        <td style="text-align:center;" > {{ date_format($date,'m/d') }}</td>
        <td style="text-align:center;" > {{ ($dtr->time_in!='00:00') ? $dtr->time_in : '' }} </td>
        <td style="text-align:center;" > {{ ($dtr->time_out!='00:00') ? $dtr->time_out : '' }} </td>
        <td style="text-align:center;" > {{ ($dtr->time_in2!='00:00') ? $dtr->time_in2 : '' }} </td>
        <td style="text-align:center;" > {{ ($dtr->time_out2!='00:00') ? $dtr->time_out2 : '' }} </td>

        <td style="text-align:center;" > {{ ($dtr->overtime_in!='00:00') ? $dtr->overtime_in : '' }} </td>
        <td style="text-align:center;" > {{ ($dtr->overtime_out!='00:00') ? $dtr->overtime_out : '' }} </td>
        <td style="text-align:center;" > {{ ($dtr->overtime_in2!='00:00') ? $dtr->overtime_in2 : '' }} </td>
        <td style="text-align:center;" > {{ ($dtr->overtime_out2!='00:00') ? $dtr->overtime_out2 : '' }} </td>
        <td style="text-align:center;" > {{ zformat($dtr->reg_day) }} </td>
        <td style="text-align:center;" > {{ zformat($dtr->overtime_hrs) }} </td>
        <td></td>
        <td></td>
        <td>{{ $dtr->remarks }}</td>
        <td></td>
        <td></td>
    </tr>
@endforeach
    <tr>
        <td colspan=10 >TOTAL</td>
        <td style="text-align:center;" >{{ zformat($gTreg_day) }}</td>
        <td style="text-align:center;" >{{ zformat($gTovertime_hrs) }}</td>
        <td></td>
        <td></td>
        <td colspan="3"></td>
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