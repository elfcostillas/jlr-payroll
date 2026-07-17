<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
<style>
    @page {
        /* size: auto;   auto is the initial value */
        margin: 45 60 45 60;  /* this affects the margin in the printer settings */
    }

    * {
        font-family: "Arial", sans-serif;
        font-size: 10pt;
    }
</style>
</head>
<body>
    <table border=0 style="width:100%;margin-bottom: 20px;">
        <tr>
            <td style="text-align: center;"> {{ $header->description }} Deduction </td>
            
        </tr>
        <tr>
            <td style="text-align: center;">{{ $header->deduction_period }}</td>
            
        </tr>
    </table>

   

    <table border=1 style="width:100%;border:1px solid black;border-collapse: collapse;">
        <tr>
            <td style="text-align:center;width:50px;"> No. </td>
            <td style="text-align:center;">Employee Name</td>
            <td style="text-align:center;width:80px;">BPN</td>
            <td style="text-align:center;width:80px;">BPS</td>
            <td style="text-align:center;width:80px;">QAD</td>
            <td style="text-align:center;width:80px;">Amount</td>
        </tr>
        @foreach($details as $detail)
        <tr>
            <td style="padding: 2px 5px;text-align:right;">{{ $loop->iteration }}</td>
            <td style="padding: 2px 5px;text-align:left;">{{ $detail->employee_name }}</td>
            <td style="padding: 2px 5px;text-align:right;">{{ ($detail->bpn > 0 ? number_format($detail->bpn,2) : '') }}</td>
            <td style="padding: 2px 5px;text-align:right;">{{ ($detail->bps > 0 ? number_format($detail->bps,2) : '') }}</td>
            <td style="padding: 2px 5px;text-align:right;">{{ ($detail->qad > 0 ? number_format($detail->qad,2) : '') }}</td>
            <td style="padding: 2px 5px;text-align:right;">{{ ($detail->amount > 0 ? number_format($detail->amount,2) : '') }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" style="font-weight: bold; padding: 2px 5px;text-align:right;">TOTAL</td>
            <td style="font-weight: bold; padding: 2px 5px;text-align:right;">{{ ($details->sum('bpn') > 0 ? number_format($details->sum('bpn'),2) : '') }}</td>
            <td style="font-weight: bold; padding: 2px 5px;text-align:right;">{{ ($details->sum('bps') > 0 ? number_format($details->sum('bps'),2) : '') }}</td>
            <td style="font-weight: bold; padding: 2px 5px;text-align:right;">{{ ($details->sum('qad') > 0 ? number_format($details->sum('qad'),2) : '') }}</td>
            <td style="font-weight: bold; padding: 2px 5px;text-align:right;">{{ ($details->sum('amount') > 0 ? number_format($details->sum('amount'),2) : '') }}</td>
        </tr>
    </table>
</body>
</html>