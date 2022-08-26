<?php
	use Carbon\Carbon;
    $rowCtr = 0;
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
</style>
<div id="rawlogbox">
    <table id="rawlogtable">
        <tr>
            <td style="font-weight:bold;">Date</td>
            <td style="font-weight:bold;">Time</td>
            <td style="font-weight:bold;">State</td>
        </tr>
        @foreach($logs as $log)
            <?php $punch_date = Carbon::createFromFormat('Y-m-d',$log->punch_date); ?>
            <tr class={{ {$rowCtr%2==0} ? 'rowEeven' : 'rowOdd';  }} >
                <td>{{ date_format($punch_date,'m/d/Y') }}</td>
                {{-- <td>{{ $log->punch_date }}</td> --}}
                <td>{{ $log->punch_time }}</td>
                <td>{{ $log->cstate }}</td>
            </tr>
            <?php $rowCtr++; ?>
        @endforeach
    </table>
</div>