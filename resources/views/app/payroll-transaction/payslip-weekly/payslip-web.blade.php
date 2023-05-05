<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * {
            font-family : 'Consolas';
            /* font-size : 9pt; */
        }

        table tr td {
            /* padding : 2px 4px; */
        }

        .payslipTable {
            margin-bottom : 8px;
        }

        .pad4 {
            padding : 0px 4px;
        }

        .headings {
            background-color: #d3d3d3;
        }

        @media print {
            body {-webkit-print-color-adjust: exact;}
        }

    </style>
</head>
<body>
    <div style="width: 720px;">

    
        @foreach($data as $e)
            <div style="width:310px;padding : 4px 16px;border : 1px solid black; margin-bottom : 8px;display:inline-block;"> 
                <table border=0 style="width:100%;" >
                    <tr>
                        <td style="width:10%;"></td>
                        <td style="width:40%;"></td>
                        <td style="width:30%;"></td>
                        <td colspan=2 style="font-size : 9pt;">{{ $e->location_name }}</td>
                    
                    </tr>
                    <tr>
                        <td colspan = 5 style="font-weight:bold;font-size:11pt;">{{ $e->employee_name }}</td>
                        
                    </tr>
                    <tr>
                        <td colspan = 3> </td>
                        <td colspan = 2 style="font-weight:bold;font-size:11pt !important;text-align:right;"> {{ number_format($e->gross_pay,2) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width:20%;font-size : 9pt;">Oth Earnings</td>
                        <td style="width:20%;font-size : 9pt;">Retro Pay</td>
                        <td ></td>
                        <td style="width:20%;font-size : 9pt;">Deduction</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width:20%;font-size : 11pt;text-align:right;border-bottom: 1px solid black;"> {{ number_format($e->earnings,2) }}</td>
                        <td style="width:20%;font-size : 11pt;text-align:right;border-bottom: 1px solid black;"> {{ number_format($e->retro_pay,2) }}</td>
                        <td></td>
                        <td style="width:20%;font-size : 11pt;text-align:right;border-bottom: 1px solid black;"> {{ number_format($e->total_deduction,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan=5>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-size : 9pt;text-align:right;white-space:nowrap;">Net Pay</td>
                        <td style="font-weight:bold;font-size:11pt !important;text-align:right;border-bottom: 4px double black"> {{ number_format($e->net_pay,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan=3 style="font-size : 8pt;text-align:left;white-space:nowrap;">Payroll Period</td>
                        <td colspan=2></td>
                    </tr>
                    <tr>
                        <td colspan=3 style="font-size : 8pt;text-align:left;white-space:nowrap;">{{ $period_label->date_range }}</td>
                        <td colspan=2></td>
                    </tr>

                    
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>