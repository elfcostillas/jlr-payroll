<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .flex-container {
            display: flex;
            flex-direction: row;
            flex-wrap : wrap;
            
        }

        .flex-container > div {
            margin : 12px;
            width : 290px;
            border: 1px solid black;
            padding : 4px 16px;
            page-break-inside:avoid;
        }

        @page {
         
        }

      
    </style>
</head>
<body>
    <div id="main" style="width:700px;" class="">
        @foreach($data as $e)
        <div style="width:290px;padding:2px 16px;border:1px solid black;display:inline-block;page-break-inside:avoid;margin: 7px 12px"> 
                <!-- <div  > -->
                    <table border=0 style="width:100%;" >
                        <tr>
                            <td style="width:10%;">&nbsp;</td>
                            <td style="width:30%;"></td>
                           
                            <td colspan=4 style="font-size : 10pt;font-weight:bold;text-align:right;">13th {{ 'Month Pay' }}</td>
                        
                        </tr>
                        <tr>
                            <td colspan = 6 style="font-weight:bold;font-size:11pt;"> {{ $e->employee_name }}</td>
                            
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan=2 style="font-size : 9pt;text-align:right;white-space:nowrap;">Net Pay</td>
                            <td colspan=3 style="font-weight:bold;font-size:11pt !important;text-align:right;border-bottom: 4px double black;"> {{ number_format($e->net_pay,2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:10pt;" >Period</td>
                        </tr>
                        <tr>
                            <td colspan="6" style="font-size:10pt;font-weight:bold" >{{ $period }}</td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        
                    </table>
                </div>
        @endforeach
    </div>
</body>
</html>