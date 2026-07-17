<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
<style>
    @page {
        /* size: auto;   auto is the initial value */
        margin: 45 45 45 45;  /* this affects the margin in the printer settings */
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

   

    <table border=1 style="width:70%;border:1px solid black;border-collapse: collapse;">
        <tr>
            <td style="text-align:center;"> No. </td>
            <td style="text-align:center;">Employee Name</td>
            <td style="text-align:center;">Amount</td>
        </tr>
        @foreach($details as $detail)
        <tr>
            <td style="padding: 2px 5px;text-align:right;">{{ $loop->iteration }}</td>
            <td style="padding: 2px 5px;text-align:left;">{{ $detail->employee_name }}</td>
            <td style="padding: 2px 5px;text-align:right;">{{ number_format($detail->amount,2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" style="padding: 2px 5px;text-align:right;">Total</td>
            <td style="padding: 2px 5px;text-align:right;">{{ number_format($details->sum('amount'),2) }}</td>
        </tr>
    </table>
</body>
</html>