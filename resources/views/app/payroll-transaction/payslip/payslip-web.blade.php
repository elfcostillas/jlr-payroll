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

    @foreach($data as $e)
        <table class="payslipTable" border=1 style="border-collapse:collapse;page-break-inside: avoid;" width="920px">
            <tr>
                <td colspan="2"> 
                    <table border=1 style="border-collapse:collapse;width:100%;">
                        <tr>
                            <td class="pad4" width="50%" style="font-size:16pt !important;" ><b>PAYSLIP </b>  </td>
                            <td class="pad4" width="50%" style="text-align:right" > <img height="48px" src="{{ asset('images/jlr-logo.jpg') }}" alt=""> </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2"> 
                    <table border=1 style="border-collapse:collapse;width:100%;">
                        <tr>
                            <td class="pad4" width="50%"><b> Payroll Period : </b> {{$period_label->date_range}} </td>
                            <td class="pad4" width="50%"><b> </b> </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="1"> 
                    <table border=1 style="border-collapse:collapse;width:100%;">
                        <tr>
                            <td class="pad4"><b> Name : {{ $e->employee_name }} {{ $e->suffixname }}  </b> </td>
                        </tr>
                    </table>
                </td>
                <td colspan="1"> 
                    <table border=1 style="border-collapse:collapse;width:100%;">
                        <tr>
                            <td class="pad4"><b> Department :  </b> {{ $e->dept_name }} </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="1"> 
                    <table border=1 style="border-collapse:collapse;width:100%;">
                        <tr>
                           @if($e->is_daily=='Y')
                            <td class="pad4"> Daily Rate : {{ number_format($e->daily_rate,2)}} </td>
                            @else
                            <td class="pad4">Monthly Rate : {{ number_format($e->basic_salary,2) }} &nbsp;&nbsp;&nbsp;&nbsp; Daily Rate : {{ number_format($e->daily_rate,2)}} </td>
                           @endif
                        </tr>
                    </table>
                </td>
                <td colspan="1"> 
                    <table border=1 style="border-collapse:collapse;width:100%;">
                        <tr>
                           
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width:50%;vertical-align:top;">
                    <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                        <tr>
                            <td colspan="3" class="pad4 headings"><b> Basic Earnings </b> </td>
                        </tr>
                        @foreach($e->basic as $b)
                            @if($b->amount>0)
                                <tr>
                                    <td class="pad4" style="text-align:left;" width="60%" > {{ $b->name }}</td>
                                    <td class="pad4" style="text-align:right;" width="10%" > {{ $b->days }}</td>
                                    <td class="pad4" style="text-align:right;padding-right:4px;" width="30%" > {{ number_format($b->amount,2) }}</td>
                                </tr>
                            @endif
                        @endforeach

                        @foreach($e->reg_earnings as $earn)
                            @if($earn->amount>0)
                                <tr>
                                    <td class="pad4" style="text-align:left;" width="60%" > {{ $earn->name }}</td>
                                    <td class="pad4" style="text-align:right;" width="10%" > {{ $earn->days }}</td>
                                    <td class="pad4" style="text-align:right;padding-right:4px;" width="30%" > {{ number_format($earn->amount,2) }}</td>
                                </tr>
                            @endif
                        @endforeach

                        @foreach($e->slvl as $leave)
                                @if($leave->amount>0)
                                    <tr>
                                        <td class="pad4" style="text-align:left;" width="60%" > {{ $leave->name }} (Hrs)</td>
                                        <td class="pad4" style="text-align:right;" width="10%" > {{ $leave->days }}</td>
                                        <td class="pad4" style="text-align:right;padding-right:4px;" width="30%" > {{ number_format($leave->amount,2) }}</td>
                                    </tr>

                                @endif
                        @endforeach
                    </table>

                    @if($e->allowances['total']>0)
                        <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                            <tr>
                                <td colspan="3" class="pad4 headings"><b>Allowances</b></td>
                            </tr>
                            @foreach($e->allowances['list'] as $allowance)
                                @if($allowance->amount>0)
                                    
                                    <tr>
                                        <td class="pad4" style="text-align:left;" width="60%" > {{ $allowance->name }}</td>
                                        <td class="pad4" style="text-align:right;" width="10%" > {{ $allowance->days }}</td>
                                        <td class="pad4" style="text-align:right;padding-right:4px;" width="30%" > {{ number_format($allowance->amount,2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>

                    @endif
                   
                    @if($e->restday['total']>0)
                        <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                            <tr>
                                <td colspan="3" class="pad4 headings"><b>Rest Day</b></td>
                            </tr>
                            @foreach($e->restday['list'] as $rd)
                                @if($rd->amount>0)
                                    <tr>
                                        <td class="pad4" style="text-align:left;" width="60%" > {{ $rd->name }}</td>
                                        <td class="pad4" style="text-align:right;" width="10%" > {{ $rd->days }}</td>
                                        <td class="pad4" style="text-align:right;padding-right:4px;" width="30%" > {{ number_format($rd->amount,2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>

                    @endif

                    @if($e->legalHol['total']>0)
                        <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                            <tr>
                                <td colspan="3" class="pad4 headings"><b>Legal Holiday</b></td>
                            </tr>
                            @foreach($e->legalHol['list'] as $reghol)
                                @if($reghol->amount>0)
                                    <tr>
                                        <td class="pad4" style="text-align:left;" width="60%" > {{ $reghol->name }}</td>
                                        <td class="pad4" style="text-align:right;" width="10%" > {{ $reghol->days }}</td>
                                        <td class="pad4" style="text-align:right;padding-right:4px;" width="30%" > {{ number_format($reghol->amount,2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>

                    @endif
                    
                    @if($e->specialHol['total']>0)
                        <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                            <tr>
                                <td colspan="3" class="pad4 headings"><b>Special Holiday</b></td>
                            </tr>
                            @foreach($e->specialHol['list'] as $sphol)
                                @if($sphol->amount>0)
                                    <tr>
                                        <td class="pad4" style="text-align:left;" width="60%" > {{ $sphol->name }}</td>
                                        <td class="pad4" style="text-align:right;" width="10%" > {{ $sphol->days }}</td>
                                        <td class="pad4" style="text-align:right;padding-right:4px;" width="30%" > {{ number_format($sphol->amount,2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>

                    @endif

                    @if($e->dblLegHol['total']>0)
                        <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                            <tr>
                                <td colspan="3" class="pad4 headings"><b>Double Legal Holiday</b></td>
                            </tr>
                            @foreach($e->dblLegHol['list'] as $dblhol)
                                @if($dblhol->amount>0)
                                    <tr>
                                        <td class="pad4" style="text-align:left;" width="60%" > {{ $dblhol->name }}</td>
                                        <td class="pad4" style="text-align:right;" width="10%" > {{ $dblhol->days }}</td>
                                        <td class="pad4" style="text-align:right;padding-right:4px;" width="30%" > {{ number_format($dblhol->amount,2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    @endif
                    <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                        <tr>
                            <td class="pad4" colspan="2" width="70%" ><b>Gross Pay</b></td>
                            <td class="pad4" style="text-align:right;padding-right:4px;"> {{ number_format($e->gross_pay,2) }} </td>
                        </tr>
                    </table>
                    @if($e->otherEearnings['total']>0)
                        <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                            <tr>
                                <td colspan="3" class="pad4 headings"><b>Other Earnings</b></td>
                            </tr>
                            @foreach($e->otherEearnings['list'] as $oe)
                                @if($oe->amount>0)
                                    <tr>
                                        <td class="pad4" style="text-align:left;" width="60%" > {{ $oe->description }}</td>
                                        <td class="pad4" style="text-align:right;" width="10%" ></td>
                                        <td class="pad4" style="text-align:right;padding-right:4px;" oe="30%" > {{ number_format($oe->amount,2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>

                    @endif
                   
                </td>
                <td style="width:50%;vertical-align:top;">
                    <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                        <tr>
                            <td colspan="2" class="pad4 headings"><b>Government Contributions</b></td>
                        </tr>
                        <tr>
                            <td class="pad4" width="70%%" >SSS Contribution</td>
                            <td class="pad4" width="30%"  style="text-align:right;padding-right:4px;">{{  ($e->sss_prem > 0) ? number_format($e->sss_prem,2) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pad4" width="70%%" >Phil Health Contribution</td>
                            <td class="pad4" width="30%"  style="text-align:right;padding-right:4px;">{{ ($e->phil_prem > 0) ? number_format($e->phil_prem,2) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pad4" width="70%%" >PAG IBIG</td>
                            <td class="pad4" width="30%"  style="text-align:right;padding-right:4px;">{{  ($e->hdmf_contri > 0) ? number_format($e->hdmf_contri,2) : '-' }}</td>
                        </tr>
                    </table>
                    @if($e->gov_loan['total']>0)
                        <table border=1 style="border-collapse:collapse;width:100%">
                            <tr>
                                <td colspan="3" class="pad4 headings"><b>Government Loans</b></td>
                            </tr>
                            @foreach($e->gov_loan['list'] as $govLoan)
                                @if($govLoan->amount>0)
                                    <tr>
                                        <tr>
                                            <td class="pad4" width="45%" ></td>
                                            <td class="pad4" width="25%"  style="text-align:right;padding-center:4px;font-size:9pt;">Running Bal.</td>
                                            <td class="pad4" width="30%"  style="text-align:right;padding-center:4px;font-size:9pt;">Current Deduction</td>
                                        </tr>
                                    </tr>
                                    <tr>
                                        <td class="pad4" >{{ $govLoan->description }}</td>
                                        <td class="pad4"  style="text-align:right;padding-right:4px;">{{ number_format($govLoan->balance,2) }}</td>
                                        <td class="pad4"  style="text-align:right;padding-right:4px;">{{ number_format($govLoan->amount,2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                        <tr>
                            <td class="pad4" colspan="2" width="70%" ><b>Gross Total</b></td>
                            <td class="pad4" style="text-align:right;padding-right:4px;"> {{ number_format($e->gross_total,2) }} </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                        <tr>
                            <td class="pad4" colspan="2" width="70%" >Total Deduction</td>
                            <td class="pad4" style="text-align:right;padding-right:4px;"> {{ number_format($e->total_deduction,2) }} </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table border=1 style="border-collapse:collapse;width:100%;margin-bottom:1px;">
                        <tr>
                            <td class="pad4" colspan="2" width="70%" style="font-size:12pt !important;" ><b>Net Pay </b> </td>
                            <td class="pad4" style="text-align:right;padding-right:4px;font-size:12pt !important;"><b> {{ number_format($e->net_pay,2) }} </b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <div style="width:920px;"> <hr style="border: none;border-top: 1px dashed black;">  </div>
        <br>
    @endforeach
</body>
</html>