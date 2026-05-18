<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Register - Managers and Supervisors</title>

    <style>
        @page {
            margin-top: 160px;
            margin-left: 8px;
            margin-right: 8px;
            margin-bottom: 24px;
          
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
            padding : 0px 2px;
        }

        .vtop {
            vertical-align: top;
          
        }

        .b {
            font-weight: bold;
        }

        table tr > td {
            padding : 0px 2px;
            /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; */
            font-family:Verdana, Geneva, Tahoma, sans-serif;
        }

        header {
            position: fixed;
            margin-top :-80px;
        }
        

        /* header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; } */
    </style>
</head>
<body>
    <header>
        <table border=0 style="width:100%;margin-bottom:2px;">
            <tr>
                <td><span style="font-size:16;" >JLR Construction and Aggregates Inc. <br>Confidential Payroll  </span></td>
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
    </header>

    <footer>

    </footer>

    <main>
        
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
                    
            function convert_hrs_to_days($hrs)
            {
                return ($hrs == '' || $hrs == 0) ? '' : $hrs / 8;
            }


        
        ?>

        <table id="main" border=1 style="width:100%;border-collapse:collapse;font-size :4pt;">
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
                @foreach ($data->data as $location)
                    <tr>
                        <td class="pad4 b" colspan="{{ $cols }}" style="padding-left:4px"> {{ $location->location_altername2 }}</td>
                    </tr>
                    @foreach ($location->divisions as $division)
                        <tr>
                            <td class="pad4 b" colspan="{{ $cols }}" style="padding-left:4px"> {{ $division->div_name }}</td>
                        </tr>

                        @foreach ($division->departments as $department)
                            <tr>
                                <td class="pad4 b" colspan="{{ $cols }}" style="padding-left:4px"> {{ $department->dept_name }} </td>
                            </tr>
                            @php
                                $ctr = 1;
                            @endphp
                            @foreach ($department->employees as $employee)
                                <tr>
                                    <td class="pad4 r" style="">{{ $ctr++ }}</td>
                                    <td class="pad4"> {{$employee->lastname}}, {{ $employee->firstname }} </td>
                                    <td class="pad4" style="" > {{$employee->job_title_name }} </td>
                                    @foreach ($data->basic_cols as $bcols)
                                    <td class="r">{{ custom_format($employee->{$bcols->var_name}) }}</td>
                                    @endforeach
                                    @foreach ($data->gross_cols as $gcols)
                                        @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                                            <td class="r">{{ convert_hrs_to_days($employee->{$gcols->var_name} )}}</td>
                                        @else
                                            <td class="r">{{ custom_format($employee->{$gcols->var_name} )}}</td>
                                        @endif
                                    
                                    @endforeach

                                    @foreach ($data->fixed_comp_hcols as $fxcols) 
                                        <td class="r"> {{ (array_key_exists($fxcols->compensation_type,$employee->other_earning) ? custom_format($employee->other_earning[$fxcols->compensation_type]) : '') }}
                                    @endforeach

                                    @foreach ($data->other_comp_hcols as $othcols) 
                                        <td class="r"> {{ (array_key_exists($othcols->compensation_type,$employee->other_earning) ? custom_format($employee->other_earning[$othcols->compensation_type]) : '') }}
                                            
                                    @endforeach
                                      <td class="r"> {{ custom_format($employee->gross_total) }} </td>

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
                                <td colspan="3" class="b" > Department Total </td>
                                @foreach ($data->basic_cols as $bcols)
                                    <td class="r b">{{ custom_format($data->computeTotalByDept($department,$bcols)) }}</td>
                                @endforeach
                                @foreach ($data->gross_cols as $gcols)

                                    @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                                        <td class="r b">{{ convert_hrs_to_days($data->computeTotalByDept($department,$gcols) )}}</td>
                                    @else
                                        <td class="r b">{{ custom_format($data->computeTotalByDept($department,$gcols) )}}</td>
                                    @endif
                                @endforeach
                                @foreach ($data->fixed_comp_hcols as $fxcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDept($department,$fxcols)) }} </td>
                                @endforeach
                                @foreach ($data->other_comp_hcols as $othcols) 
                                    <td class="r b"> {{ custom_format($data->computeTotalByDept($department,$othcols)) }} </td>
                                @endforeach
                                <td class="r b">  {{ custom_format($data->computeTotalByDept($department,'gross_total')) }} </td>
                                @foreach ($data->contri as $contri_cols)
                                    <td class="r b">{{ custom_format($data->computeTotalByDept($department,$contri_cols)) }}</td>
                                @endforeach

                                @foreach ($data->deduction_hcols as $deduction_hcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDept($department,$deduction_hcols)) }} </td>
                                @endforeach
                                @foreach ($data->govloans_hcols as $govloans_hcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDept($department,$govloans_hcols)) }} </td>
                                @endforeach
                                <td class="r b">  {{ custom_format($data->computeTotalByDept($department,'total_deduction')) }} </td>
                                <td class="r b">  {{ custom_format($data->computeTotalByDept($department,'net_pay')) }} </td>
              
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="b" > Division Total </td>
                                @foreach ($data->basic_cols as $bcols)
                                    <td class="r b">{{ custom_format($data->computeTotalByDivision($division,$bcols)) }}</td>
                                @endforeach
                                @foreach ($data->gross_cols as $gcols)

                                    @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                                        <td class="r b">{{ convert_hrs_to_days($data->computeTotalByDivision($division,$gcols) )}}</td>
                                    @else
                                        <td class="r b">{{ custom_format($data->computeTotalByDivision($division,$gcols) )}}</td>
                                    @endif
                                @endforeach
                                @foreach ($data->fixed_comp_hcols as $fxcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDivision($division,$fxcols)) }} </td>
                                @endforeach
                                @foreach ($data->other_comp_hcols as $othcols) 
                                    <td class="r b"> {{ custom_format($data->computeTotalByDivision($division,$othcols)) }} </td>
                                @endforeach
                                <td class="r b">  {{ custom_format($data->computeTotalByDivision($division,'gross_total')) }} </td>
                                @foreach ($data->contri as $contri_cols)
                                    <td class="r b">{{ custom_format($data->computeTotalByDivision($division,$contri_cols)) }}</td>
                                @endforeach

                                @foreach ($data->deduction_hcols as $deduction_hcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDivision($division,$deduction_hcols)) }} </td>
                                @endforeach
                                @foreach ($data->govloans_hcols as $govloans_hcols)
                                    <td class="r b"> {{ custom_format($data->computeTotalByDivision($division,$govloans_hcols)) }} </td>
                                @endforeach
                                <td class="r b">  {{ custom_format($data->computeTotalByDivision($division,'total_deduction')) }} </td>
                                <td class="r b">  {{ custom_format($data->computeTotalByDivision($division,'net_pay')) }} </td>
              
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="b" > Location Total </td>
                        @foreach ($data->basic_cols as $bcols)
                            <td class="r b">{{ custom_format($data->computeTotalByLocation($location,$bcols)) }}</td>
                        @endforeach
                        @foreach ($data->gross_cols as $gcols)

                            @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                                <td class="r b">{{ convert_hrs_to_days($data->computeTotalByLocation($location,$gcols) )}}</td>
                            @else
                                <td class="r b">{{ custom_format($data->computeTotalByLocation($location,$gcols) )}}</td>
                            @endif
                        @endforeach
                        @foreach ($data->fixed_comp_hcols as $fxcols)
                            <td class="r b"> {{ custom_format($data->computeTotalByLocation($location,$fxcols)) }} </td>
                        @endforeach
                        @foreach ($data->other_comp_hcols as $othcols) 
                            <td class="r b"> {{ custom_format($data->computeTotalByLocation($location,$othcols)) }} </td>
                        @endforeach
                        <td class="r b">  {{ custom_format($data->computeTotalByLocation($location,'gross_total')) }} </td>
                        @foreach ($data->contri as $contri_cols)
                            <td class="r b">{{ custom_format($data->computeTotalByLocation($location,$contri_cols)) }}</td>
                        @endforeach

                        @foreach ($data->deduction_hcols as $deduction_hcols)
                            <td class="r b"> {{ custom_format($data->computeTotalByLocation($location,$deduction_hcols)) }} </td>
                        @endforeach
                        @foreach ($data->govloans_hcols as $govloans_hcols)
                            <td class="r b"> {{ custom_format($data->computeTotalByLocation($location,$govloans_hcols)) }} </td>
                        @endforeach
                        <td class="r b">  {{ custom_format($data->computeTotalByLocation($location,'total_deduction')) }} </td>
                        <td class="r b">  {{ custom_format($data->computeTotalByLocation($location,'net_pay')) }} </td>
              
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="b" > Overall Total </td>
                    @foreach ($data->basic_cols as $bcols)
                        <td class="r b">{{ custom_format($data->computeOverAll($data,$bcols)) }}</td>
                    @endforeach
                    @foreach ($data->gross_cols as $gcols)

                        @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                            <td class="r b">{{ convert_hrs_to_days($data->computeOverAll($data,$gcols) )}}</td>
                        @else
                            <td class="r b">{{ custom_format($data->computeOverAll($data,$gcols) )}}</td>
                        @endif
                    @endforeach
                    @foreach ($data->fixed_comp_hcols as $fxcols)
                        <td class="r b"> {{ custom_format($data->computeOverAll($data,$fxcols)) }} </td>
                    @endforeach
                    @foreach ($data->other_comp_hcols as $othcols) 
                        <td class="r b"> {{ custom_format($data->computeOverAll($data,$othcols)) }} </td>
                    @endforeach
                    <td class="r b">  {{ custom_format($data->computeOverAll($data,'gross_total')) }} </td>
                    @foreach ($data->contri as $contri_cols)
                        <td class="r b">{{ custom_format($data->computeOverAll($data,$contri_cols)) }}</td>
                    @endforeach

                    @foreach ($data->deduction_hcols as $deduction_hcols)
                        <td class="r b"> {{ custom_format($data->computeOverAll($data,$deduction_hcols)) }} </td>
                    @endforeach
                    @foreach ($data->govloans_hcols as $govloans_hcols)
                        <td class="r b"> {{ custom_format($data->computeOverAll($data,$govloans_hcols)) }} </td>
                    @endforeach
                    <td class="r b">  {{ custom_format($data->computeOverAll($data,'total_deduction')) }} </td>
                    <td class="r b">  {{ custom_format($data->computeOverAll($data,'net_pay')) }} </td>
                </tr>
        </table>

        <table style="page-break-before:always;float:left;font-size :6pt;border-collapse:collapse; margin-top : 12px;" border=1>
            <tr> 
                <td style="padding : 2px 6px;"> Departments</td>
                @foreach ($data->summaryDeptByLocationOriginal()['x'] as $cols)
                    <td style=";padding : 2px 6px;">
                        {{ $cols->location_altername2 }}
                    </td>
                @endforeach
                <td style="padding : 2px 6px;"> TOTALS</td>
                @foreach ($data->summaryDeptByLocationOriginal()['y'] as $rows)
                    <tr> 
                        <td style="padding : 0px 6px;">
                            {{ $rows->dept_label }}
                        </td>
                   
                        @foreach ($data->summaryDeptByLocationOriginal()['x'] as $cols)
                            <td style="text-align: right;padding-right:6px;">
                               {{  ($data->summaryDeptByLocationOriginal()['data'][$rows->id][$cols->id] > 0) ? $data->summaryDeptByLocationOriginal()['data'][$rows->id][$cols->id] : '' }}
                            </td>
                        @endforeach
                        <td style="text-align: right;padding-right:6px;">
                           {{ $data->summaryDeptByLocationOriginal()['totals_by_dept'][$rows->id] }}
                        </td>
                    </tr> 
                @endforeach
                <td style="padding : 2px 6px;"> TOTALS</td>
                @foreach ($data->summaryDeptByLocationOriginal()['x'] as $cols)
                    <td style="text-align: right;padding : 2px 6px;">
                        {{ $data->summaryDeptByLocationOriginal()['totals_by_loc'][$cols->id] }}
                    </td>
                @endforeach
                    <td style="text-align: right;padding : 2px 6px;">
                        {{ $data->summaryDeptByLocationOriginal()['over_all'] }}
                    </td>
            </tr>
            
        </table>

        <!-- @foreach ($data->countPerJobTitleLocationOriginal() as $location)
           
            @if($location->data->count() > 0)
            @php $count_total =0;  @endphp
             <table style="padding-left: 12px;float:left;font-size :6pt;border-collapse:collapse; margin-top : 12px;" border=1>
                <tr>
                    <td colspan="3" > {{ $location->location_altername2 }} </td>
                </tr>
                @foreach ($location->data as $row)
                    @php $count_total +=  $row->pax;  @endphp
                    <tr>
                        <td> {{ $row->dept_label }}</td>
                        <td> {{ $row->job_title_name }}</td>
                        <td style="text-align: right;padding : 2px 6px;"> {{ $row->pax }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"> TOTAL </td>
                    <td style="text-align: right;padding : 2px 6px;"> {{ $count_total }} </td>
                </tr>
            </table>
            @endif
        @endforeach -->

        <table style="float:left ;font-size :6pt;border-collapse:collapse;margin-top:12px;margin-left:12px" border=1>
        <!-- <table style="font-size :6pt;border-collapse:collapse;margin-top:184px;" border=1> -->
            <tr> 
                <td colspan="2" style="text-align: center;padding:2px 6px;" > Payroll / Gross Pay </td> 
            </tr>
            @php 
                $total_gross = 0;
            @endphp
            @foreach ( $data->total_pay_per_dept_original() as $gross_dept)
                @php 
                    $total_gross += $gross_dept->gross_total;
                @endphp
                <tr>
                    <td style="padding:2px 6px;"> {{  $gross_dept->dept_label }} </td>
                    <td style="padding:2px 6px;text-align:right;"> {{  number_format($gross_dept->gross_total,2) }} </td>
                </tr>
              
            @endforeach
                <tr>
                    <td> TOTAL </td>
                    <td style="padding:2px 6px;text-align:right;"> {{  number_format($total_gross,2) }} </td>
                </tr> 
        </table>

        <!-- <table style="float:left;margin-left:272px;font-size :6pt;border-collapse:collapse;margin-top:284px;clear:left;" border=1> -->
        <table style="float:left;margin-left:12px;font-size :6pt;border-collapse:collapse;margin-top:12px;" border=1>
            <tr> 
                <td colspan=2 style="text-align: center;padding:2px 6px;" > Overtime Summary </td> 
            </tr>

            @foreach ($data->otSummary() as $key => $value )
                <tr>
                    <td style="padding:2px 6px;"> {{ $key }} </td>
                    <td style="padding:2px 6px;text-align:right;"> {{ ($value > 0) ? (int) $value : ''  }} </td>
                </tr>
            @endforeach
        </table>

        <?php $starting_margin = 12; ?>

        @foreach ($data->otSummary() as $key => $value )
            @if($value > 0)
                <?php
                    $ot_data = $data->otByDeptJobtitleOriginal($key);
                ?>

                <table style="width:230px;float:left;margin-left:{{ $starting_margin }}px;font-size :6pt;border-collapse:collapse;margin-top:228px;clear:left;" border=1>
                    <tr>
                        <td style="text-align:center;" colspan="3"> {{ $key }} </td>
                    </tr>
                    @foreach ($ot_data as $row)
                    <tr>
                        <td> {{ $row->dept_label }}</td>
                        <td>{{ $row->job_title_name }} - {{ $row->div_code }}</td>
                        <td> {{ $row->pax}} </td>
                    </tr>
                        
                    @endforeach
                </table>

                <?php $starting_margin += 238; ?>
            @endif
        @endforeach

        @if ($data->otMoreThan50hrsOriginal()->count()>0)
            <table style="width:140px;float:left;margin-left:{{ $starting_margin }}px;font-size :6pt;border-collapse:collapse;margin-top:284px;clear:left;" border=1>
                <tr>
                    <td style="text-align:center;" colspan="2"> Overtime >= 50++ </td>
                </tr>
                @foreach ($data->otMoreThan50hrsOriginal() as $row )
                    <tr>
                        <td> {{ $row->div_code }} </td>
                        <td style="text-align:right;";>{{ $row->pax }}</td>
                    </tr>
                @endforeach
              
            </table>
        @endif

        <table style="width:100%;margin-top:468px;font-size:8pt;" border=0>
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
                <td style="height:25px"></td>
                <td style="text-align:left;vertical-align:bottom" ></td>
                <td style="text-align:left;vertical-align:bottom" ></td>
                <td style="text-align:left;vertical-align:bottom" ></td>
                <td></td>
                
            </tr>
            <tr>
                <td></td>
                <td class="l"> {{ Auth::user()->name }}</td>
                <td class="l">Herbert B. Camasura </td>
                <td class="l">Gershwin Ralph G. Alvarez </td>
                <td></td>
            </tr>
        </table>

    </main>
</body>
</html>


