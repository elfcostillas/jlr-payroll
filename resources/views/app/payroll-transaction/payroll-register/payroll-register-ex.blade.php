<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
            * {
                font-family: 'Consolas';
                font-size : 9pt;
            }
    </style>
</head>
<body>
 
    <?php
        $colspan=25;

        $grandCtr = 0;
    //   <img src="{{ public_path('images/header-logo.jpg') }}" style="height:24px;" class="center" >
    ?>
    <div id="" >
        <div>
        
        </div>
        <table>
            <tr >
                <td style="height:92px;"></td>
            </tr>
            <tr>
                <td style="font-weight:bold;" >JLR Construction and Aggregates Inc.</td>
            </tr>
            <tr>
                <td> {{ $payperiod_label }}</td>
            </tr>
        </table>
        <table style="border-collapse:collapse;white-space:nowrap;" border=1 >
            <thead>
                
                <tr>    
                        <td> No. </td>
                        <td > Bio ID</td>
                        <td >Name</td>
                        <td >Basic Rate</td>
                        <td >Daily Rate</td>
                        <td >Allowance  <br> (Monthly)</td>
                        <td >Allowance  <br> (Daily)</td>
                        <td >No Days</td>
                        <td >Basic Pay</td>
                        
                        <td >Daily <br> Allowance</td>
                        <td >Semi Monthly <br> Allowance</td>

                        <td >Late <br>(Hrs)</td>
                        <td >Late Amount</td>

                        <td >Undertime <br>(Hrs)</td>
                        <td >Undertime <br>Amount</td>

                        <td >VL</td>
                        <td >VL Amount</td>

                        <td >SL</td>
                        <td >SL Amount</td>

                        <td >BL (Hrs)</td>
                        <td >BL Amount</td>

                        <td >SVL</td>
                        <td >SVL Amount</td>

                        <td >Absent</td>
                        <td >Absent <br> Amount</td>


                        @foreach($headers as $key => $val)
                            <td  width ="90px" ><p>{{ $labels[$key] }}</p></td>
                            @php $colspan++; @endphp
                        @endforeach
                        <td >Gross Pay</td>
                        @foreach($compensation as $comp)
                            <td  width ="90px" style="white-space:normal"><p>{{ $comp->description }}</p></td>
                            @php $colspan++; @endphp
                        @endforeach
                        <td >Gross Total</td>
                        <td >SSS Premium</td>
                        <td >SSS MPF</td>
                        <td >PhilHealt <br> Premium</td>
                        <td >PAG IBIG <br> Contri</td> 
                        <td >Withholding Tax</td> 
                         @php $colspan+=3; @endphp
                        @foreach($govLoan as $glabel)
                            <td width ="90px" style="white-space:normal"><p>{{ $glabel->description }}</p></td>
                            @php $colspan++; @endphp
                        @endforeach
                        
                        @foreach($deductionLabel as $label)
                            <td width ="90px" style="white-space:normal"><p>{{ $label->description }}</p></td>
                            @php $colspan++; @endphp
                        @endforeach

                        <td >Total Deduction</td>
                        <td >Net Pay</td>
                </tr>
            </thead>
            <?php 
                            
                $overALL['basic_salary'] = 0;
                $overALL['daily_rate'] = 0;

                $overALL['mallowance'] = 0;
                $overALL['dallowance'] = 0;

                $overALL['ndays'] = 0;
                $overALL['basic_pay'] = 0;
                $overALL['daily_allowance'] = 0;
                $overALL['semi_monthly_allowance'] = 0;

                $overALL['late_eq'] = 0;
                $overALL['late_eq_amount'] = 0;
                $overALL['under_time'] = 0;
                $overALL['under_time_amount'] = 0;

                $overALL['vl_wpay'] = 0;
                $overALL['vl_wpay_amount'] = 0;
                $overALL['sl_wpay'] = 0;
                $overALL['sl_wpay_amount'] = 0;
                $overALL['bl_wpay'] = 0;
                $overALL['bl_wpay_amount'] = 0;
                $overALL['absences'] = 0;
                $overALL['absences_amount'] = 0;

                $overALL['svl'] = 0;
                $overALL['svl_amount'] = 0;

                $overALL['gross_pay'] = 0;
                $overALL['gross_total'] = 0;

                foreach($headers as $key => $val)
                {
                    $deptDIVALL[$key] = 0;
                }

                foreach($compensation as $comp)
                {
                    $deptCompDIVALL[$comp->id] = 0;
                }

                $overALL['sss'] = 0;
                $overALL['wisp'] = 0;
                $overALL['phic'] = 0;
                $overALL['hdmf'] = 0;
                $overALL['wtax'] = 0;

                foreach($govLoan as $gkey => $glabel)
                {
                    $govDedDIVALL[$glabel->id] = 0;
                }
                
                foreach($deductionLabel as $key => $label)
                {
                    $compDedDIVALL[$label->id] = 0;
                }

                $overALL['total_deduction'] = 0;
                $overALL['net_pay'] = 0;
                            
            ?>

                    @foreach($data as $division)  
                        <tr>
                            <td colspan={{$colspan}}  class="division"> {{ $division->div_name }} </td>
                        </tr>
                            <?php 
                          
                                $divTotal['basic_salary'] = 0;
                                $divTotal['daily_rate'] = 0;

                                $divTotal['mallowance'] = 0;
                                $divTotal['dallowance'] = 0;

                                $divTotal['ndays'] = 0;
                                $divTotal['basic_pay'] = 0;
                                $divTotal['daily_allowance'] = 0;
                                $divTotal['semi_monthly_allowance'] = 0;

                                $divTotal['late_eq'] = 0;
                                $divTotal['late_eq_amount'] = 0;
                                $divTotal['under_time'] = 0;
                                $divTotal['under_time_amount'] = 0;

                                $divTotal['vl_wpay'] = 0;
                                $divTotal['vl_wpay_amount'] = 0;
                                $divTotal['sl_wpay'] = 0;
                                $divTotal['sl_wpay_amount'] = 0;
                                $divTotal['bl_wpay'] = 0;
                                $divTotal['bl_wpay_amount'] = 0;
                                $divTotal['absences'] = 0;
                                $divTotal['absences_amount'] = 0;

                                $divTotal['svl'] = 0;
                                $divTotal['svl_amount'] = 0;

                                $divTotal['gross_pay'] = 0;
                                $divTotal['gross_total'] = 0;

                                foreach($headers as $key => $val)
                                {
                                    $deptDIV[$key] = 0;
                                }

                                foreach($compensation as $comp)
                                {
                                    $deptCompDIV[$comp->id] = 0;
                                }

                                $divTotal['sss'] = 0;
                                $divTotal['wisp'] = 0;
                                $divTotal['phic'] = 0;
                                $divTotal['hdmf'] = 0;
                                $divTotal['wtax'] = 0;

                                foreach($govLoan as $gkey => $glabel)
                                {
                                    $govDedDIV[$glabel->id] = 0;
                                }
                                
                                foreach($deductionLabel as $key => $label)
                                {
                                    $compDedDIV[$label->id] = 0;
                                }

                                $divTotal['total_deduction'] = 0;
                                $divTotal['net_pay'] = 0;
                                            
                            ?>
                        
                        @foreach($division->departments as $department)
                            <tr>
                                <td colspan={{$colspan}}  class="department"> {{ $department->dept_name }} </td>
                            </tr>
                            <?php 
                                $ctr = 1;
                               

                                $dept['basic_salary'] = 0; 
                                $dept['daily_rate'] = 0;

                                $dept['mallowance'] = 0;
                                $dept['dallowance'] = 0;

                                $dept['ndays'] = 0; 
                                $dept['basic_pay'] = 0; 
                                $dept['daily_allowance'] = 0; 
                                $dept['semi_monthly_allowance'] = 0; 

                                $dept['late_eq'] = 0;
                                $dept['late_eq_amount'] = 0; 
                                $dept['under_time'] = 0;
                                $dept['under_time_amount'] = 0;

                                $dept['vl_wpay'] = 0;
                                $dept['vl_wpay_amount'] = 0; 
                                $dept['sl_wpay'] = 0; 
                                $dept['sl_wpay_amount'] = 0; 
                                $dept['bl_wpay'] = 0;
                                $dept['bl_wpay_amount'] = 0;
                                $dept['absences'] = 0; 
                                $dept['absences_amount'] = 0;

                                $dept['svl'] = 0; 
                                $dept['svl_amount'] = 0;

                                $dept['gross_pay'] = 0; 
                                $dept['gross_total'] = 0;

                                foreach($headers as $key => $val)
                                {
                                    $dept[$key] = 0;
                                }

                                foreach($compensation as $comp)
                                {
                                    $deptComp[$comp->id] = 0;
                                }

                                $dept['sss'] = 0; 
                                $dept['wisp'] = 0; 
                                $dept['phic'] = 0; 
                                $dept['hdmf'] = 0; 
                                $dept['wtax'] = 0; 

                                foreach($govLoan as $gkey => $glabel)
                                {
                                    $govDed[$glabel->id] = 0;
                                }
                                
                                foreach($deductionLabel as $key => $label)
                                {
                                    $compDed[$label->id] = 0;
                                }

                                $dept['total_deduction'] = 0; 
                                $dept['net_pay'] = 0; 
                                            
                            ?>
                            @foreach($department->employees as $employee)
                                <?php
                                
                                    $dept['basic_salary'] += $employee->basic_salary;
                                    $dept['daily_rate'] += $employee->daily_rate;
                                    $dept['mallowance'] += $employee->mallowance;
                                    $dept['dallowance'] += $employee->dallowance;
                                    $dept['ndays'] += $employee->ndays;
                                    $dept['basic_pay'] += $employee->basic_pay;
                                    $dept['daily_allowance'] += $employee->daily_allowance;
                                    $dept['semi_monthly_allowance'] += $employee->semi_monthly_allowance;
                                    $dept['late_eq'] += $employee->late_eq;
                                    $dept['late_eq_amount'] += $employee->late_eq_amount;
                                    $dept['under_time'] += $employee->under_time;
                                    $dept['under_time_amount'] += $employee->under_time_amount;
                                    $dept['vl_wpay'] += $employee->vl_wpay;
                                    $dept['vl_wpay_amount'] += $employee->vl_wpay_amount;
                                    $dept['sl_wpay'] += $employee->sl_wpay;
                                    $dept['sl_wpay_amount'] += $employee->sl_wpay_amount;
                                    $dept['bl_wpay'] += $employee->bl_wpay;
                                    $dept['bl_wpay_amount'] += $employee->bl_wpay_amount;
                                    $dept['absences'] += $employee->absences;
                                    $dept['absences_amount'] += $employee->absences_amount;

                                    $dept['svl'] += $employee->svl;
                                    $dept['svl_amount'] += $employee->svl_amount;

                                    $dept['gross_pay'] += $employee->gross_pay;
                                    $dept['gross_total'] += $employee->gross_total;

                                    foreach($headers as $key => $val)
                                    {
                                        $dept[$key] += $employee->{$key};
                                    }

                                    foreach($compensation as $comp)
                                    {
                                        $deptComp[$comp->id] += (array_key_exists($comp->id,$employee->otherEarnings)) ? $employee->otherEarnings[$comp->id] : 0;
                                    }

                                    foreach($govLoan as $gkey => $glabel)
                                    {
                                        $govDed[$glabel->id] += (array_key_exists($glabel->id,$employee->loans)) ? $employee->loans[$glabel->id] : 0;
                                    }

                                    foreach($deductionLabel as $key => $label)
                                    {
                                        $compDed[$label->id] +=(array_key_exists($label->id,$employee->deductions)) ? $employee->deductions[$label->id] : 0;
                                    }

                                    $dept['sss'] += $employee->gov_deductions['SSS Premium'];
                                    $dept['wisp'] += $employee->gov_deductions['SSS WISP'];
                                    $dept['phic'] += $employee->gov_deductions['PhilHealt Premium'];
                                    $dept['hdmf'] += $employee->gov_deductions['PAG IBIG Contri'];
                                    $dept['wtax'] += $employee->gov_deductions['WTAx'];

                                    $dept['total_deduction'] += $employee->total_deduction;
                                    $dept['net_pay'] += $employee->net_pay;

                                    /* Per DIVISION */

                                    $divTotal['basic_salary'] += $employee->basic_salary;
                                    $divTotal['daily_rate'] += $employee->daily_rate;
                                    $divTotal['mallowance'] += $employee->mallowance;
                                    $divTotal['dallowance'] += $employee->dallowance;
                                    $divTotal['ndays'] += $employee->ndays;
                                    $divTotal['basic_pay'] += $employee->basic_pay;
                                    $divTotal['daily_allowance'] += $employee->daily_allowance;
                                    $divTotal['semi_monthly_allowance'] += $employee->semi_monthly_allowance;
                                    $divTotal['late_eq'] += $employee->late_eq;
                                    $divTotal['late_eq_amount'] += $employee->late_eq_amount;
                                    $divTotal['under_time'] += $employee->under_time;
                                    $divTotal['under_time_amount'] += $employee->under_time_amount;
                                    $divTotal['vl_wpay'] += $employee->vl_wpay;
                                    $divTotal['vl_wpay_amount'] += $employee->vl_wpay_amount;
                                    $divTotal['sl_wpay'] += $employee->sl_wpay;
                                    $divTotal['sl_wpay_amount'] += $employee->sl_wpay_amount;
                                    $divTotal['bl_wpay'] += $employee->bl_wpay;
                                    $divTotal['bl_wpay_amount'] += $employee->bl_wpay_amount;
                                    $divTotal['absences'] += $employee->absences;
                                    $divTotal['absences_amount'] += $employee->absences_amount;
                                    $divTotal['gross_pay'] += $employee->gross_pay;
                                    $divTotal['gross_total'] += $employee->gross_total;

                                    $divTotal['svl'] += $employee->svl;
                                    $divTotal['svl_amount'] += $employee->svl_amount;

                                    foreach($headers as $key => $val)
                                    {
                                        $deptDIV[$key] += $employee->{$key};
                                    }

                                    foreach($compensation as $comp)
                                    {
                                        $deptCompDIV[$comp->id]  += (array_key_exists($comp->id,$employee->otherEarnings)) ? $employee->otherEarnings[$comp->id] : 0;
                                    }

                                    foreach($govLoan as $gkey => $glabel)
                                    {
                                        $govDedDIV[$glabel->id] += (array_key_exists($glabel->id,$employee->loans)) ? $employee->loans[$glabel->id] : 0;
                                    }
                                    
                                    foreach($deductionLabel as $key => $label)
                                    {
                                        $compDedDIV[$label->id] +=(array_key_exists($label->id,$employee->deductions)) ? $employee->deductions[$label->id] : 0;
                                    }

                                    $divTotal['sss'] += $employee->gov_deductions['SSS Premium'];
                                    $divTotal['wisp'] += $employee->gov_deductions['SSS WISP'];
                                    $divTotal['phic'] += $employee->gov_deductions['PhilHealt Premium'];
                                    $divTotal['hdmf'] += $employee->gov_deductions['PAG IBIG Contri'];
                                    $divTotal['wtax'] += $employee->gov_deductions['WTAx'];

                                    $divTotal['total_deduction'] += $employee->total_deduction;
                                    $divTotal['net_pay'] += $employee->net_pay;

                                    /* Over ALL Total*/

                                    $overALL['basic_salary'] += $employee->basic_salary;
                                    $overALL['daily_rate'] += $employee->daily_rate;
                                    $overALL['mallowance'] += $employee->mallowance;
                                    $overALL['dallowance'] += $employee->dallowance;
                                    $overALL['ndays'] += $employee->ndays;
                                    $overALL['basic_pay'] += $employee->basic_pay;
                                    $overALL['daily_allowance'] += $employee->daily_allowance;
                                    $overALL['semi_monthly_allowance'] += $employee->semi_monthly_allowance;
                                    $overALL['late_eq'] += $employee->late_eq;
                                    $overALL['late_eq_amount'] += $employee->late_eq_amount;
                                    $overALL['under_time'] += $employee->under_time;
                                    $overALL['under_time_amount'] += $employee->under_time_amount;
                                    $overALL['vl_wpay'] += $employee->vl_wpay;
                                    $overALL['vl_wpay_amount'] += $employee->vl_wpay_amount;
                                    $overALL['sl_wpay'] += $employee->sl_wpay;
                                    $overALL['sl_wpay_amount'] += $employee->sl_wpay_amount;
                                    $overALL['bl_wpay'] += $employee->bl_wpay;
                                    $overALL['bl_wpay_amount'] += $employee->bl_wpay_amount;
                                    $overALL['absences'] += $employee->absences;
                                    $overALL['absences_amount'] += $employee->absences_amount;
                                    $overALL['gross_pay'] += $employee->gross_pay;
                                    $overALL['gross_total'] += $employee->gross_total;

                                    foreach($headers as $key => $val)
                                    {
                                        $deptDIVALL[$key] += $employee->{$key};
                                    }

                                    foreach($compensation as $comp)
                                    {
                                        $deptCompDIVALL[$comp->id]  += (array_key_exists($comp->id,$employee->otherEarnings)) ? $employee->otherEarnings[$comp->id] : 0;
                                    }

                                    foreach($govLoan as $gkey => $glabel)
                                    {
                                        $govDedDIVALL[$glabel->id] += (array_key_exists($glabel->id,$employee->loans)) ? $employee->loans[$glabel->id] : 0;
                                    }
                                    
                                    foreach($deductionLabel as $key => $label)
                                    {
                                        $compDedDIVALL[$label->id] +=(array_key_exists($label->id,$employee->deductions)) ? $employee->deductions[$label->id] : 0;
                                    }

                                    $overALL['sss'] += $employee->gov_deductions['SSS Premium'];
                                    $overALL['wisp'] += $employee->gov_deductions['SSS WISP'];
                                    $overALL['phic'] += $employee->gov_deductions['PhilHealt Premium'];
                                    $overALL['hdmf'] += $employee->gov_deductions['PAG IBIG Contri'];
                                    $overALL['wtax'] += $employee->gov_deductions['WTAx'];

                                    $overALL['total_deduction'] += $employee->total_deduction;
                                    $overALL['net_pay'] += $employee->net_pay;

                                    $grandCtr++;
                                   
                                ?>
                              
                                <tr >
                                    <td> {{ $ctr++ }} </td>
                                    <td> {{ $employee->biometric_id }} </td> 
                                    <td > {{ $employee->employee_name }} </td> 
                                    <td > {{ $employee->basic_salary }}</td>
                                    <td > {{ $employee->daily_rate }}</td>

                                    <td > {{ ($employee->mallowance>0) ? $employee->mallowance : '' }}</td>
                                    <td > {{ ($employee->dallowance>0) ? $employee->dallowance : '' }}</td>
                                    
                                    <td > {{ $employee->ndays }}</td>
                                    <td > {{ $employee->basic_pay }}</td>

                                    <td > {{ ($employee->daily_allowance>0) ? $employee->daily_allowance : ''; }}</td>
                                    <td > {{ ($employee->semi_monthly_allowance>0) ? $employee->semi_monthly_allowance : ''; }}</td>

                                    <td > {{ ($employee->late_eq>0) ? $employee->late_eq : ''; }}</td>
                                    <td > {{ ($employee->late_eq_amount>0) ? $employee->late_eq_amount : ''; }}</td>
                                    <td > {{ ($employee->under_time>0) ? $employee->under_time : ''; }}</td>
                                    <td > {{ ($employee->under_time_amount>0) ? $employee->under_time_amount : ''; }}</td>
                                
                                    <td > {{ ($employee->vl_wpay>0) ? $employee->vl_wpay : ''; }}</td>
                                    <td > {{ ($employee->vl_wpay_amount>0) ? $employee->vl_wpay_amount : ''; }}</td>
                                    
                                    <td > {{ ($employee->sl_wpay>0) ? $employee->sl_wpay : ''; }}</td>
                                    <td > {{ ($employee->sl_wpay_amount>0) ? $employee->sl_wpay_amount : ''; }}</td>

                                    <td > {{ ($employee->bl_wpay>0) ? $employee->bl_wpay : ''; }}</td>
                                    <td > {{ ($employee->bl_wpay_amount>0) ? $employee->bl_wpay_amount : ''; }}</td>
                                    <td>  {{ ($employee->svl>0) ? $employee->svl : ''; }}</td>
                                    <td  >{{ ($employee->svl_amount>0) ? $employee->svl_amount : ''; }}</td>
                                    <td > {{ ($employee->absences_amount>0) ? $employee->absences : ''; }}</td>
                                    <td > {{ ($employee->absences_amount>0) ? $employee->absences_amount : ''; }}</td>
                                    
                                    @foreach($headers as $key => $val)
                                        <td >{{ ($employee->$key > 0) ? $employee->$key : '' }}</td>
                                    @endforeach
                                        <td>{{ ($employee->gross_pay > 0) ? $employee->gross_pay : '' }}</td>
                                    @foreach($compensation as $comp)
                                       
                                        <td  > {{ (array_key_exists($comp->id,$employee->otherEarnings)) ? $employee->otherEarnings[$comp->id] : ''; }}</td>
                                       
                                    @endforeach
                                        <td >{{ ($employee->gross_total>0) ? $employee->gross_total : ''; }}</td>
                                        <td  >{{ ($employee->gov_deductions['SSS Premium']>0) ? $employee->gov_deductions['SSS Premium'] : ''; }}</td>
                                        <td  >{{ ($employee->gov_deductions['SSS WISP']>0) ? $employee->gov_deductions['SSS WISP'] : ''; }}</td>
                                        <td  >{{ ($employee->gov_deductions['PhilHealt Premium']>0) ? $employee->gov_deductions['PhilHealt Premium'] : ''; }}</td>
                                        <td  >{{ ($employee->gov_deductions['PAG IBIG Contri']>0) ? $employee->gov_deductions['PAG IBIG Contri'] : ''; }}</td>
                                        <td  >{{ ($employee->gov_deductions['WTAx']>0) ? $employee->gov_deductions['WTAx'] : ''; }}</td>

                                    @foreach($govLoan as $gkey => $glabel)
                                       
                                        <td  >{{ (array_key_exists($glabel->id,$employee->loans)) ? $employee->loans[$glabel->id] : ''; }}</td>
                                    @endforeach
                                    @foreach($deductionLabel as $key => $label)
                                        <td  >{{ (array_key_exists($label->id,$employee->deductions)) ? $employee->deductions[$label->id] : ''; }}</td> 
                                    @endforeach
                                    <td >{{ ($employee->total_deduction>0) ? $employee->total_deduction : ''; }}</td>
                                    <td >{{ ($employee->net_pay>0) ? $employee->net_pay : ''; }}</td>
                                </tr>

                                <?php
                                    
                                ?>
                            @endforeach
                            <tr>
                                <td style="font-weight:bold;" >TOTAL</td>
                                <td></td>
                                <td></td>
                                <td style="font-weight:bold;" >{{$dept['basic_salary']}}</td>
                                <td style="font-weight:bold;" >{{ $dept['daily_rate'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['mallowance'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['dallowance'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['ndays'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['basic_pay'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['daily_allowance'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['semi_monthly_allowance'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['late_eq'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['late_eq_amount'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['under_time'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['under_time_amount'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['vl_wpay'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['vl_wpay_amount'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['sl_wpay'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['sl_wpay_amount'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['bl_wpay'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['bl_wpay_amount'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['svl'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['svl_amount'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['absences_amount'] }}</td>
                                <td style="font-weight:bold;" >{{ $dept['absences_amount'] }}</td>
                                @foreach($headers as $key => $val)
                                    <td style="font-weight:bold;" > {{ $dept[$key] }} </td>
                                @endforeach
                                    <td style="font-weight:bold;" >{{ $dept['gross_pay'] }}</td>
                                @foreach($compensation as $comp)
                                    <td style="font-weight:bold;" > {{ $deptComp[$comp->id] }} </td>   
                                @endforeach
                                    <td style="font-weight:bold;" >{{ $dept['gross_total'] }}</td>

                                    <td style="font-weight:bold;" >{{ $dept['sss'] }}</td>
                                    <td style="font-weight:bold;" >{{ $dept['wisp'] }}</td>
                                    <td style="font-weight:bold;" >{{ $dept['phic'] }}</td>
                                    <td style="font-weight:bold;" >{{ $dept['hdmf'] }}</td>
                                    <td style="font-weight:bold;" >{{ $dept['wtax'] }}</td>
                                @foreach($govLoan as $gkey => $glabel)
                                    <td style="font-weight:bold;" >{{ $govDed[$glabel->id] }}</td>
                                @endforeach

                                @foreach($deductionLabel as $key => $label)
                                    <td style="font-weight:bold;"  >{{ $compDed[$label->id] }}</td>
                                @endforeach
                                    <td style="font-weight:bold;" >{{ $dept['total_deduction'] }}</td>
                                    <td style="font-weight:bold;" >{{ $dept['net_pay'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="font-weight:bold;"  >TOTAL per DIVISION</td>
                            <td></td>
                            <td></td>
                            <td style="font-weight:bold;"  >{{ $divTotal['basic_salary']}}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['daily_rate'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['mallowance'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['dallowance'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['ndays'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['basic_pay'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['daily_allowance'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['semi_monthly_allowance'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['late_eq'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['late_eq_amount'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['under_time'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['under_time_amount'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['vl_wpay'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['vl_wpay_amount'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['sl_wpay'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['sl_wpay_amount'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['bl_wpay'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['bl_wpay_amount'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['svl'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['svl_amount'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['absences_amount'] }}</td>
                            <td style="font-weight:bold;"  >{{ $divTotal['absences_amount'] }}</td>
                            @foreach($headers as $key => $val)
                                <td style="font-weight:bold;"  > {{ $deptDIV[$key] }} </td>
                            @endforeach
                            <td style="font-weight:bold;" >{{ $divTotal['gross_pay'] }}</td>
                            @foreach($compensation as $comp)
                                <td  style="font-weight:bold;" > {{ $deptComp[$comp->id] }} </td>   
                            @endforeach
                                <td style="font-weight:bold;"  >{{ $divTotal['gross_total'] }}</td>

                                <td style="font-weight:bold;"  >{{ $divTotal['sss'] }}</td>
                                <td style="font-weight:bold;"  >{{ $divTotal['wisp'] }}</td>
                                <td style="font-weight:bold;"  >{{ $divTotal['phic'] }}</td>
                                <td style="font-weight:bold;"  >{{ $divTotal['hdmf'] }}</td>
                                <td style="font-weight:bold;"  >{{ $divTotal['wtax'] }}</td>
                            @foreach($govLoan as $gkey => $glabel)
                                <td  style="font-weight:bold;"  >{{ $govDedDIV[$glabel->id] }}</td>
                            @endforeach

                            @foreach($deductionLabel as $key => $label)
                                <td  style="font-weight:bold;"  >{{ $compDedDIV[$label->id] }}</td>
                            @endforeach
                                <td  style="font-weight:bold;"   >{{ $divTotal['total_deduction'] }}</td>
                                <td  style="font-weight:bold;"  >{{ $divTotal['net_pay'] }}</td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    @endforeach
                    <tr  >
                        <td style="font-weight:bold;" >OVER ALL TOTAL</td>
                        <td></td>
                        <td></td>
                        <td  style="font-weight:bold;" >{{ $overALL['basic_salary']}}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['daily_rate'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['mallowance'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['dallowance'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['ndays'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['basic_pay'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['daily_allowance'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['semi_monthly_allowance'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['late_eq'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['late_eq_amount'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['under_time'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['under_time_amount'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['vl_wpay'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['vl_wpay_amount'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['sl_wpay'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['sl_wpay_amount'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['bl_wpay'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['bl_wpay_amount'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['svl'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['svl_amount'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['absences_amount'] }}</td>
                        <td  style="font-weight:bold;" >{{ $overALL['absences_amount'] }}</td>
                        @foreach($headers as $key => $val)
                           <td style="font-weight:bold;"  > {{ $deptDIVALL[$key] }} </td>
                        @endforeach
                            <td style="font-weight:bold;"  >{{ $overALL['gross_pay'] }}</td>
                        @foreach($compensation as $comp)
                            <td style="font-weight:bold;"  > {{ $deptCompDIVALL[$comp->id] }} </td>   
                        @endforeach
                            <td style="font-weight:bold;"  >{{ $overALL['gross_total'] }}</td>

                            <td style="font-weight:bold;"  >{{ $overALL['sss'] }}</td>
                            <td style="font-weight:bold;"  >{{ $overALL['wisp'] }}</td>
                            <td style="font-weight:bold;"  >{{ $overALL['phic'] }}</td>
                            <td style="font-weight:bold;"  >{{ $overALL['hdmf'] }}</td>
                            <td style="font-weight:bold;"  >{{ $overALL['wtax'] }}</td>
                        @foreach($govLoan as $gkey => $glabel)
                            <td style="font-weight:bold;"  >{{ $govDedDIVALL[$glabel->id] }}</td>
                        @endforeach

                        @foreach($deductionLabel as $key => $label)
                            <td  style="font-weight:bold;"   >{{ $compDedDIVALL[$label->id] }}</td>
                        @endforeach
                            <td  style="font-weight:bold;" >{{ $overALL['total_deduction'] }}</td>
                            <td  style="font-weight:bold;" >{{ $overALL['net_pay'] }}</td>
                    </tr>
        </table>
        <table>
            <tr>
                <td> No of Employees :</td> <td> {{ $grandCtr }}</td>
            </tr>
        </table>
        @if(count($no_pay)>0)
        <table>
            <tr>
                <td colspan="2"> Employees not in computation</td>
            </tr>
            <tr>
                <td>Biometric ID</td>
                <td>Employee Name</td>
            </tr>

            @foreach($no_pay as $e)
                <tr>
                    <td> {{ $e->biometric_id }}</td>
                    <td> {{ $e->employee_name }}</td>
                    
                </tr>
            @endforeach
        </table>
        @endif
    </div>
</body>
</html>