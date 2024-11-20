<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @page {
            margin-top: 58px;
            margin-bottom : 20px;
        }

        /* #main {
            page-break-inside: auto;
        } */

      
    </style>
</head>
<body>
    <?php
        $ctr = 1;
    ?>
    <div id="main">
      
            @foreach($data as $e)
              
                <!-- <div style ></div> -->
                <div style="width:305px;padding : 4px 16px;border : 1px solid black;margin-right:4px;margin-bottom : 8px;display:inline-block;page-break-inside:avoid"> 
                    <table border=0 style="width:100%;" >
                        <tr>
                            <td style="width:10%;"></td>
                            <td style="width:40%;"></td>
                            <td style="width:30%;"></td>
                            <td colspan=3 style="font-size : 9pt;"></td>
                        
                        </tr>
                        <tr>
                            <td colspan = 6 style="font-weight:bold;font-size:11pt;"> {{ $e->employee_name }}</td>
                            
                        </tr>
                        <tr>
                            <td colspan = 4> </td>
                            <td colspan = 2 style="font-weight:bold;font-size:11pt !important;text-align:right;"> {{ number_format($e->gross_pay,2) }}</td>
                        </tr>
                        <tr>
                            <td colspan=6>
                                <table style="width:100%" border=0>
                                    <tr>
                                    
                                        <td style="font-size : 8pt;">Oth Earnings</td>
                                        <td style="font-size : 8pt;">Retro Pay</td>
                                        <td style="font-size : 8pt;">PPE</td>
                                        <td style="font-size : 8pt;">Canteen</td>
                                        <td style="font-size : 8pt;">Cash Advance</td>
                                        <td style="font-size : 8pt;">Office Acct</td>
                                       
                                    </tr>
                                    <tr>
                                        <td style="font-size : 9pt;text-align:right;border-bottom: 1px solid black;padding: 0px 6px;"> {{ number_format($e->otherEearnings['earnings'],2) }}</td>
                                        <td style="font-size : 9pt;text-align:right;border-bottom: 1px solid black;padding: 0px 6px;"> {{ number_format($e->otherEearnings['retro_pay'],2) }}</td>
                                        <td style="font-size : 9pt;text-align:right;border-bottom: 1px solid black;padding: 0px 6px;"> {{ number_format($e->deductions['deductions'],2) }}</td>
                                        <td style="font-size : 9pt;text-align:right;border-bottom: 1px solid black;padding: 0px 6px;"> {{ number_format($e->deductions['canteen'],2) }}</td>
                                        <td style="font-size : 9pt;text-align:right;border-bottom: 1px solid black;padding: 0px 6px;"> {{ number_format($e->deductions['cash_advance'],2) }}</td>
                                        <td style="font-size : 9pt;text-align:right;border-bottom: 1px solid black;padding: 0px 6px;"> {{ number_format($e->deductions['office_account'],2) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=6>
                                <table style="width:100%" border=0>
                                    <tr>
                                        <td style="font-size : 8pt;">SSS Prem</td>
                                        <td style="font-size : 8pt;">PHIL Prem</td>
                                        <td style="font-size : 8pt;">HDMF Contri</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size : 9pt;text-align:right;border-bottom: 1px solid black;padding: 0px 6px;"> {{ number_format($e->sss_prem,2) }}</td>
                                        <td style="font-size : 9pt;text-align:right;border-bottom: 1px solid black;padding: 0px 6px;"> {{ number_format($e->phil_prem,2) }}</td>
                                        <td style="font-size : 9pt;text-align:right;border-bottom: 1px solid black;padding: 0px 6px;"> {{ number_format($e->hdmf_contri,2) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=6>&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                           
                            <td style="font-size : 9pt;text-align:right;white-space:nowrap;">Net Pay</td>
                            <td colspan=3 style="font-weight:bold;font-size:11pt !important;text-align:right;border-bottom: 4px double black"> {{ number_format($e->net_pay,2) }}</td>
                        </tr>
                        <tr>
                            <td colspan=3 style="font-size : 8pt;text-align:left;white-space:nowrap;">Payroll Period</td>
                            <td colspan=3></td>
                        </tr>
                        <tr>
                            <td colspan=3 style="font-size : 8pt;text-align:left;white-space:nowrap;">{{ $period_label->date_range }}</td>
                            <td colspan=3></td>
                        </tr>

                        
                    </table>
                </div>
                @php
                    $ctr++;


                @endphp
          
            @endforeach
        
    </div>
</body>
</html>