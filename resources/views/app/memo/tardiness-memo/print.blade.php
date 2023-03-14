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

    if($data->memo_date){

    }else {
        dd("Please select memo date.");
    }
    $memo_date = Carbon::createFromFormat('Y-m-d',$data->memo_date);
?>

<style>
    @font-face {
        font-family: Helvetica;
        src: url({{storage_path('/fonts/Helvetica.ttf')}}) format('truetype');

    }

    * {
        font-family : "Helvetica";
        font-size : 10pt;
    }

    table {
        font-size :10pt;
        page-break-inside: avoid; 
        border-collapse:collapse;
        margin-bottom : 4px;
    }
 
    tr { 
        page-break-inside:avoid; 
        page-break-after:auto 
    }

    td {
        padding : 2px;
    }

    @page { margin: 30px 75px 20px 75px; border:1px solid green } /* top right bottom left */

</style>
<body>
    <div style="top:0;height:68px;width:100%;text-align:center;">
        <img src="{{ public_path('images/header-logo.jpg') }}" style="height:66px;" class="center" >
    </div>
<main>
    <table style="width:100%" border=0 >
        <tr>
            <td style="width:80px" >To</td>
            <td style="width:40px">:</td>
            <td colspan=6 style="font-weight:bold;">{{ $data->memo_to }}</td>
            
        </tr>
        <tr>
            <td style="width:80px" >From</td>
            <td>:</td>
            <td colspan=6>{{ $data->memo_from }}</td>
         
        </tr>
        <tr>
            <td style="width:80px" >Date</td>
            <td>:</td>
            <td colspan=6>{{ $memo_date->format('M  d, Y') }}</td>         
        </tr>
        <tr>
            <td style="width:80px" >Subject</td>
            <td>:</td>
            <td colspan=6 style="font-weight:bold;">{{ $data->memo_subject }}</td>
        </tr>
    </table>
    <hr>
        <p style="white-space:pre-line;">{{ $data->memo_upper_body }}</p>
 
        <table border=1 style="width:80%;border-collapse:collapse">
            <tr>
                <td style="width:10%;padding-left:9px;">No</td>
                <td style="width:25%;padding-left:9px;"> Date</td>
                <td style="width:40%;padding-left:9px;">Time Logs</td>
                <td style="width:25%;padding-left:9px;"></td>
            </tr>
            @php $ctr=1; @endphp
            @foreach($details as $d)
                @php
                    $dtr_date = Carbon::createFromFormat('Y-m-d',$d->dtr_date);
                @endphp
                <tr>
                    <td style="text-align:center">{{ $ctr++ }} </td>
                    <td style="padding-left:9px;">{{ $dtr_date->format('m/d/Y') }}</td>
                    <td style="padding-left:9px;"> {{ $d->time_in }} - {{ $d->time_out }}</td>
                    <td style="padding-left:9px;"> {{ round( $d->in_minutes/60,2) }}</td>
                </tr>
            @endforeach
        </table>
    {{-- <p style="white-space: pre-line">
        {{ $breakdown.$total }}
        {{ $total }}
    </p> --}}
   
    
    <?php
        //dd(count($details));

        if($total_count <= 10){
            $action = "Disciplinary action is corrective counseling.";
        }

        if($total_count >= 11 && $total_count <=15){
            $action = "Corrective action is written warning.";
        }

        if($total_count >= 16 && $total_count <=18){
            $action = "Corrective action is Suspension of three (3) days.";
        }

        if($total_count == 19){
            $action = "Corrective action is Suspension of seven (7) days.";
        }

        if($total_count == 20){
            $action = "Corrective action is Suspension of fifteen (15) days or dismissal.";
        }

        if($total_count >= 21){
            $action = "Corrective action is dismissal.";
        }

        $str = "Further commission of the same offense within a quarter shall progress to a corrective action including written warning, suspension from work without pay up to dismissal from employment.";
    ?>

    <p style="white-space: pre-line">
        {{ $breakdown.' '.$total }}
        
        {{ $data->memo_lower_body.$action }}
    </p>

    <p style="white-space: pre-line">{{ $str }}</p>
    <p style="white-space: pre-line">This is for your guidance.</p>
    <table border=0 style="width:100%;font-size :11px !important" >
        <tr>
            <td style="width:25%">{{ $data->prep_by_text }}</td>
            <td style="width:10%"></td>
            <td style="width:25%">{{ $data->noted_by_text }}</td>
            <td style="width:10%"></td>
            <td style="width:30%">{{ $data->noted_by_text_dept }}</td>
        </tr>
        <tr>
            <td height="40px">&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td> {{ $data->prep_by_name }}</td>
            <td></td>
            <td> {{ $data->noted_by_name }}</td>
            <td></td>
            <td> {{ $data->noted_by_name_dept }}</td>
        </tr>
        <tr>
            <td> {{ $data->prep_by_position }}</td>
            <td></td>
            <td> {{ $data->noted_by_position }}</td>
            <td></td>
            <td> {{ $data->noted_by_position_dept }}</td>
        </tr>
        <tr>
            <td colspan=5 height="20px">&nbsp;</td>
        </tr>
        <tr>
            <td colspan=5>Received by:</td>
        </tr>
        <tr>
            <td colspan=5 height="30px">&nbsp;</td>
        </tr>
        <tr>
            <td colspan=5>
                 _____________________________ <br>
                Signature over printed name / Date <br>
                <span style="font-size:9pt !important;">Cc: file/ immediate head</span>
            </td>
        </tr>
    </table>
</main>       
</body>
</html>


<!-- "id" => "4"
    "biometric_id" => "1501"
    "memo_to" => "Antimaro, Elmer G. "
    "memo_from" => "Human Resource Department"
    "memo_date" => "2023-03-13"
    "memo_subject" => "TARDINESS REPORT FOR <Month and Year>"
    "memo_upper_body" => """
      This is in relation to the tardiness you incured for the month of <Month nd Year> as shown
      below.
      """
    "memo_lower_body" => """
      Above number of tardiness occurence is a violation to the company policy under the Code of
      Conduct Section I. Attendance and Punctuality, revised QMS-MEMO- 06 012320. Disciplinary
      Action is Written Warning.
      
      Further commision of the same offense within a quarter shall progress to Suspension of
      three (3) days.
      
      This is for your guidance.
      """
    "prep_by_text" => "Prepared by :"
    "prep_by_name" => null
    "prep_by_position" => "HR Staff"
    "noted_by_text" => "Noted by:"
    "noted_by_name" => null
    "noted_by_position" => "HR Manager"
    "noted_by_text_dept" => "Noted by:"
    "noted_by_name_dept" => null
    "noted_by_position_dept" => "<Department> Manager"
    "memo_month" => "2"
    "memo_year" => "2023" -->