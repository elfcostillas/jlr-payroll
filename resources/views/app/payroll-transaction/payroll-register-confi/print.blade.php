<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Register - Managers and Supervisors</title>

    <style>
        @page {
            margin-top: 80px;
            margin-left: 15px;
            margin-right: 15px;
            margin-bottom: 30px;
        }

        .r {
            text-align: right;
        }

        .l {
            text-align: left;
        }

        .c {
            text-align: center;
        }

        .pad4 {
            padding : 0px 4px;
        }

        .vtop {
            vertical-align: top;
        }

        .b {
            font-weight: bold;
        }

        table tr > td {
            padding : 0px 3px;
            /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; */
            font-family:Verdana, Geneva, Tahoma, sans-serif;
        }
    </style>
</head>
<body>
    <table border=0 style="width:100%;margin-bottom:2px;">
        <tr>
            <td><span style="font-size:16;" >JLR Construction and Aggregates Inc. <br>Semi Monthly Payroll  </span></td>
            <td style="font-size:12pt;vertical-align:bottom" >Payroll Period :<u style="font-size:12pt;vertical-align:bottom"> {{ $label }} </u></td>
            <td style="width:24px" ></td>
            <td style="width:26%;font-size:12pt;padding-left:24px;vertical-align:bottom" >Date / Time  Printed : {{ now()->format('m/d/Y H:i:s') }} </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    
    <?php
  
        $cols = 6 + count($data->basic_cols) + count($data->gross_cols) + count($data->fixed_comp_hcols) 
            + count($data->other_comp_hcols) + count($data->contri) + count($data->deduction_hcols)
            + count($data->govloans_hcols);

        function custom_format($n)
        {
            $blank = [0,'',null];

            if(in_array($n,$blank))
            {
                return '';
            }else{
                return number_format($n,2);
            }
        }

       
    ?>

    <table id="main" border=1 style="width:100%;border-collapse:collapse;font-size :5pt;">
        @foreach ($data->data as $location)
            <tr>
                <td class="pad4" class="b" colspan={{ $cols }}> {{ $location->location_altername2 }} </td>
            </tr>
            <tr>
                <td class="vtop c b">No.</td>
                <td class="vtop c b">Name</td>
                <td class="vtop c b">Job Title</td>
                @foreach ($data->basic_cols as $bcols)
                    <td class="vtop c b">{{ $bcols->col_label }}</td>
                @endforeach
                @foreach ($data->gross_cols as $gcols)
                    <td class="vtop c b">{{ $gcols->col_label }}</td>
                @endforeach

                @foreach ($data->fixed_comp_hcols as $fxcols)
                    <td class="vtop c b">{{ $fxcols->description }}</td>
                @endforeach

                @foreach ($data->other_comp_hcols as $othcols)
                    <td class="vtop c b">{{ $othcols->description }}</td>
                @endforeach
                <td class="vtop c b"> Gross Total </td>
                @foreach ($data->contri as $contricols)
                    <td class="vtop c b">{{ $contricols->col_label }}</td>
                @endforeach

                @foreach ($data->deduction_hcols as $deduction_cols)
                    <td class="vtop c b">{{ $deduction_cols->description }}</td>
                @endforeach

                @foreach ($data->govloans_hcols as $govloans_hcols)
                    <td class="vtop c b">{{ $govloans_hcols->description }}</td>
                @endforeach

                    <td class="vtop c b">Total Deduction</td>
                    <td class="vtop c b">Net Pay</td>

                
            </tr>
            @foreach($location->divisions as $division)
                <tr>
                    <td class="pad4 b" colspan={{ $cols }} style="padding-left:42px"> {{ $division->div_name }}</td>
                </tr>
                @foreach ($division->departments as $department)
                    <tr>
                        <td class="pad4 b" colspan={{ $cols }} style="padding-left:84px"> {{ $department->dept_name }} </td>
                    </tr>
                    @php
                        $ctr = 1;
                    @endphp
                    
                    
                   
                    @foreach ($department->employees as $employee)
                        <tr>
                            <td class="pad4 r" style="width:16px">{{ $ctr++ }}</td>
                            <td class="pad4" > {{$employee->lastname}}, {{ $employee->firstname }} </td>
                            <td class="pad4" > {{$employee->job_title_name }} </td>
                            @foreach ($data->basic_cols as $bcols)
                               <td class="r">{{ custom_format($employee->{$bcols->var_name}) }}</td>
                            @endforeach
                            @foreach ($data->gross_cols as $gcols)
                                <td class="r">{{ custom_format($employee->{$gcols->var_name} )}}</td>
                            @endforeach
                            
                            @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                                <td class="r"> {{ (array_key_exists($fxcols->compensation_type,$employee->other_earning) ? custom_format($employee->other_earning[$fxcols->compensation_type]) : '') }}
                            @endforeach

                            @foreach ($data->other_comp_hcols as $othcols) <!-- Other Compensation -->
                                <td class="r"> {{ (array_key_exists($othcols->compensation_type,$employee->other_earning) ? custom_format($employee->other_earning[$othcols->compensation_type]) : '') }}
                                    
                            @endforeach

                            <td class="r"> {{ custom_format($employee->gross_total) }} </td> <!-- Gross Total-->

                            @foreach ($data->contri as $contri_cols)
                                <td class="r">{{ custom_format($employee->{$contri_cols->var_name}) }}</td>
                            @endforeach
                            @foreach ($data->deduction_hcols as $deduction_hcols)
                                <td class="r"> {{ (array_key_exists($deduction_hcols->id,$employee->deductions) ? custom_format($employee->deductions[$deduction_hcols->id]) : '') }}
                                
                            @endforeach
                            @foreach ($data->govloans_hcols as $govloans_hcols)
                                 <td class="r"> {{ (array_key_exists($govloans_hcols->id,$employee->gov_loans) ? custom_format($employee->gov_loans[$govloans_hcols->id]) : '') }}
                            @endforeach
                            <td class="r"> {{ custom_format($employee->total_deduction) }} </td>
                            <td class="r"> {{ custom_format($employee->net_pay) }}</td>


                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" > Total Dept </td>
                        @foreach ($data->basic_cols as $bcols)
                            <td class="r b">{{ custom_format($data->computeTotalByDept($department,$bcols)) }}</td>
                        @endforeach
                        @foreach ($data->gross_cols as $gcols)
                            <td class="r b">{{ custom_format($data->computeTotalByDept($department,$gcols) )}}</td>
                        @endforeach
                        @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                            <td class="r b"> {{ custom_format($data->computeTotalOtherEarningByDept($department,$fxcols)) }} </td>
                        @endforeach
                        @foreach ($data->other_comp_hcols as $othcols) <!-- Fixed Compensation -->
                            <td class="r b"> {{ custom_format($data->computeTotalOtherEarningByDept($department,$othcols)) }} </td>
                        @endforeach
                        
                        <td class="r b">  {{ custom_format($data->computeTotalByDept($department,'gross_total')) }} </td>
                        
                        @foreach ($data->contri as $contri_cols)
                            <td class="r b">{{ custom_format($data->computeTotalByDept($department,$contri_cols)) }}</td>
                        @endforeach

                        @foreach ($data->deduction_hcols as $deduction_hcols)
                            <!-- <td class="r b"> {{ (array_key_exists($deduction_hcols->id,$employee->deductions) ? custom_format($employee->deductions[$deduction_hcols->id]) : '') }}  -->
                             <td class="r b"> {{ custom_format($data->computeTotalDeductionsByDept($department,$deduction_hcols)) }} </td>
                        @endforeach
                        @foreach ($data->govloans_hcols as $govloans_hcols)
                            <td class="r b"> {{ custom_format($data->computeTotalLoansByDept($department,$govloans_hcols)) }} </td>
                        @endforeach
                        <td class="r b">  {{ custom_format($data->computeTotalByDept($department,'total_deduction')) }} </td>
                        <td class="r b">  {{ custom_format($data->computeTotalByDept($department,'net_pay')) }} </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="b" > Total Division </td>
                    @foreach ($data->basic_cols as $bcols)
                        <td class="r b"  >{{ custom_format($data->computeTotalByDivision($division,$bcols)) }}</td>
                    @endforeach
                    @foreach ($data->gross_cols as $gcols)
                        <td class="r b"  >{{ custom_format($data->computeTotalByDivision($division,$gcols) )}}</td>
                    @endforeach
                    @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                        <td class="r b"  > {{ custom_format($data->computeTotalOtherEarningByDivision($division,$fxcols)) }} </td>
                    @endforeach
                    @foreach ($data->other_comp_hcols as $othcols) <!-- Fixed Compensation -->
                        <td class="r b"  > {{ custom_format($data->computeTotalOtherEarningByDivision($division,$othcols)) }} </td>
                    @endforeach
                    <td class="r b"  >  {{ custom_format($data->computeTotalByDivision($division,'gross_total')) }} </td>

                    @foreach ($data->contri as $contri_cols)
                        <td class="r b"  >{{ custom_format($data->computeTotalByDivision($division,$contri_cols)) }}</td>
                    @endforeach

                    @foreach ($data->deduction_hcols as $deduction_hcols)
                        <!-- <td class="r b"> {{ (array_key_exists($deduction_hcols->id,$employee->deductions) ? custom_format($employee->deductions[$deduction_hcols->id]) : '') }}  -->
                            <td class="r b"  > {{ custom_format($data->computeTotalDeductionsByDivision($division,$deduction_hcols)) }} </td>
                    @endforeach
                    @foreach ($data->govloans_hcols as $govloans_hcols)
                            <td class="r b"> {{ custom_format($data->computeTotalLoanByDivision($division,$govloans_hcols)) }} </td>
                    @endforeach
                    <td class="r b"  >  {{ custom_format($data->computeTotalByDivision($division,'total_deduction')) }} </td>
                    <td class="r b"  >  {{ custom_format($data->computeTotalByDivision($division,'net_pay')) }} </td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="3" class="b" > Overall Total </td>
            @foreach ($data->basic_cols as $bcols)
                <td class="r b"  >{{ custom_format($data->computeOverAll($data,$bcols)) }}</td>
            @endforeach
            @foreach ($data->gross_cols as $gcols)
                <td class="r b"  >{{ custom_format($data->computeOverAll($data,$gcols) )}}</td>
            @endforeach
            @foreach ($data->fixed_comp_hcols as $fxcols) <!-- Fixed Compensation -->
                <td class="r b"  > {{ custom_format($data->computeOtherEarningsOverAll($data,$fxcols)) }} </td>
            @endforeach
            @foreach ($data->other_comp_hcols as $othcols) <!-- Fixed Compensation -->
                <td class="r b"  > {{ custom_format($data->computeOtherEarningsOverAll($data,$othcols)) }} </td>
            @endforeach
            <td class="r b"  >  {{ custom_format($data->computeOverAll($data,'gross_total')) }} </td>
            @foreach ($data->contri as $contri_cols)
                <td class="r b"  >{{ custom_format($data->computeOverAll($data,$contri_cols)) }}</td>
            @endforeach
            @foreach ($data->deduction_hcols as $deduction_hcols)
                <td class="r b"  > {{ custom_format($data->computeTotalDeductionsOverall($data,$deduction_hcols)) }} </td>
            @endforeach
            @foreach ($data->govloans_hcols as $govloans_hcols)
                    <td class="r b"  > {{ custom_format($data->computeTotalLoanOverAll($data,$govloans_hcols)) }} </td>
            @endforeach
            <td class="r b"  >  {{ custom_format($data->computeOverAll($data,'total_deduction')) }} </td>
            <td class="r b"  >  {{ custom_format($data->computeOverAll($data,'net_pay')) }} </td>
        </tr>
    </table>

    <table style="width:100%;margin-top:88px;font-size:8pt;" border=0>
        <tr>
            <td style="width:10%">
            <td style="width:26%">
                Prepared By :
            </td>
            <td style="width:26%">
                Noted by :
            </td>
            <td style="width:26%">
                Checked by :
            </td>
            <td style="width:10%">
        </tr>
        <tr>
            <td style="height:40px"></td>
            <td style="text-align:left;vertical-align:bottom" ></td>
            <td style="text-align:left;vertical-align:bottom" ></td>
            <td style="text-align:left;vertical-align:bottom" ></td>
            <td></td>
            
        </tr>
        <tr>
            <td></td>
            <td class="l"> {{ Auth::user()->name }}</td>
            <td class="l">Herbert B. Camasura, </td>
            <td class="l">Gershwin Ralph G. Alvarez </td>
            <td></td>
        </tr>
    </table>
</body>
</html>

