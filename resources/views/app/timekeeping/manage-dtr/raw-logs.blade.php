<?php
	use Carbon\Carbon;
    $rowCtr = 0;
    $cdate = '';
?>
<style>
    #rawlogbox {
        color : white;
    }

    #rawlogtable {
        font-family: 'Consolas';
        font-size : 9pt !important;
        
    }

    table#rawlogtable  tr  td {
        padding : 2px 8px;
    }

    .rowEven {
        background-color : #bebebe;
    }

    .tbheader{
        background-color : #949494;
    }
</style>
<div id="rawlogbox" style="vertical-align:top;min-height:220px;">
    <table id="rawlogtable" style="border-collapse:collapse;">
        <tr class="tbheader">
            <td style="font-weight:bold;">Date</td>
            <td style="font-weight:bold;">Time</td>
            <td style="font-weight:bold;">State</td>
        </tr>
        @foreach($logs as $log)
            <?php 
                $punch_date = Carbon::createFromFormat('Y-m-d',$log->punch_date); 
                if($cdate!=$punch_date){
                    $cdate = $punch_date;
                    $rowCtr++;
                }
                
            ?>
            <tr class={{ ($rowCtr%2==0) ? 'rowEven' : 'rowOdd';  }}>
                <td>{{ date_format($punch_date,'m/d/Y') }}</td>
                <td>{{ $log->punch_time }}</td>
                <td>{{ $log->cstate }}</td>
            </tr>
        @endforeach
    </table>
</div>