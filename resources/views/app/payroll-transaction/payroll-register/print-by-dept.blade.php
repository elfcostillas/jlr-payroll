<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Register - Rank and File</title>

    <style>
        @page {
            margin-top: 160px;
            margin-left: 8px;
            margin-right: 8px;
            margin-bottom: 28px;
          
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
                <td><span style="font-size:16;" >JLR Construction and Aggregates Inc. <br>Rank and File Payroll  </span></td>
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

            function custom_total_format($n)
            {
                $blank = [0,'',null];

                if(in_array($n,$blank))
                {
                    return number_format(0,2);
                }else{
                    return number_format($n,2);
                }
            }


                    
            function convert_hrs_to_days($hrs)
            {
                // return ($hrs == '' || $hrs == 0) ? '' : $hrs / 8;
                return round($hrs,2);
            }


        
        ?>

        <table id="main" border=1 style="page-break-after :always;width:100%;border-collapse:collapse;font-size:3.5pt;">
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
                
                @foreach ($data->data as $department)
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

                            @php  
                                // @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                            @endphp
                            @foreach ($data->gross_cols as $gcols)
                                @if (in_array($gcols->var_name,[]))
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
                    <td colspan="3" class="b" > Overall Total </td>
                    @foreach ($data->basic_cols as $bcols)
                        <td class="r b">{{ custom_total_format($data->getOverAllTotalDepartmentTier($data,$bcols)) }}</td>
                    @endforeach
                    @foreach ($data->gross_cols as $gcols)

                        @if (in_array($gcols->var_name,['vl_wpay','sl_wpay','absences','bl_wpay','svl']))
                            <td class="r b">{{ convert_hrs_to_days($data->getOverAllTotalDepartmentTier($data,$gcols) )}}</td>
                        @else
                            <td class="r b">{{ custom_total_format($data->getOverAllTotalDepartmentTier($data,$gcols) )}}</td>
                        @endif
                    @endforeach
                    @foreach ($data->fixed_comp_hcols as $fxcols)
                       
                       
                        <td class="r b"> {{ custom_total_format($data->getOverAllTotalDepartmentTier($data,$fxcols)) }} </td>
                    @endforeach
                    @foreach ($data->other_comp_hcols as $othcols) 
                        <td class="r b"> {{ custom_total_format($data->getOverAllTotalDepartmentTier($data,$othcols)) }} </td>
                    @endforeach
                    <td class="r b">  {{ custom_total_format($data->getOverAllTotalDepartmentTier($data,'gross_total')) }} </td>
                    @foreach ($data->contri as $contri_cols)
                        <td class="r b">{{ custom_total_format($data->getOverAllTotalDepartmentTier($data,$contri_cols)) }}</td>
                    @endforeach

                    @foreach ($data->deduction_hcols as $deduction_hcols)
                        <td class="r b"> {{ custom_total_format($data->getOverAllTotalDepartmentTier($data,$deduction_hcols)) }} </td>
                    @endforeach
                    @foreach ($data->govloans_hcols as $govloans_hcols)
                        <td class="r b"> {{ custom_total_format($data->getOverAllTotalDepartmentTier($data,$govloans_hcols)) }} </td>
                    @endforeach
                    <td class="r b">  {{ custom_total_format($data->getOverAllTotalDepartmentTier($data,'total_deduction')) }} </td>
                    <td class="r b">  {{ custom_total_format($data->getOverAllTotalDepartmentTier($data,'net_pay')) }} </td>
                </tr>

                
        </table>

        <?php $head_count = 0; $over_all_total_gross = 0; $over_all_total_net = 0; ?>
        <table style="float:left;font-size :6pt;border-collapse:collapse; margin-top : 12px;" border=1>
            <tr>
                <td style="padding:2px 6px;">Division</td>
                <td style="padding:2px 6px;">Department</td>
                <td style="padding:2px 6px;">Count</td>
                <!-- <td style="padding:2px 6px;">Gross Pay By Dept.</td>
                <td style="padding:2px 6px;">Avg. Gross Pay By Dept.</td>
                <td style="padding:2px 6px;">Net Pay By Dept.</td> -->
            </tr>
            @foreach ($data->getCountsByDiviosionDept() as $division )
            <tr>
                <?php $start = true; ?>
                <td style="padding:2px 6px;" rowspan="{{ $division->departments->count() }}" >{{ $division->div_code }} </td>
                
                @foreach ( $division->departments as $department)
                    @if (!$start)
                        <tr>
                    @endif
                        <td style="padding:2px 6px;"> {{ $department->dept_code }} </td>    
                        <td style="padding:2px 6px;text-align:right;"> {{ $department->bio_count }} </td>    
                        <!-- <td style="padding:2px 6px;text-align:right;"> {{ number_format($department->gross_total,2) }} </td>    
                        <td style="padding:2px 6px;text-align:right;"> {{ number_format(($department->gross_total/$department->bio_count),2) }} </td>    
                        <td style="padding:2px 6px;text-align:right;"> {{ number_format($department->net_pay,2) }} </td>     -->
                    </tr>
                    @php $start = false; 
                        $head_count += $department->bio_count; 
                        $over_all_total_gross += $department->gross_total; 
                        $over_all_total_net += $department->net_pay; 
                    @endphp
                @endforeach
            @endforeach
            <tr>
                <td colspan="2"> TOTAL </td>
                <td class="b" style="padding:2px 6px;text-align:right;"> {{ $head_count }} </td>
                <!-- <td class="b" style="padding:2px 6px;text-align:right;"> {{ number_format($over_all_total_gross,2) }} </td>
                <td class="b" style="padding:2px 6px;text-align:right;"> {{ number_format($over_all_total_gross/$head_count,2) }} </td>
                <td class="b" style="padding:2px 6px;text-align:right;"> {{ number_format($over_all_total_net,2) }} </td> -->

            </tr>
        </table>

        <table style="float:left;font-size :6pt;border-collapse:collapse;margin-left : 8px; margin-top : 12px;" border=1>
            <tr>
                <td style="padding:2px 6px;">Division</td>
                <td style="padding:2px 6px;">Department</td>
               
                <td style="padding:2px 6px;width:50px;" class="c">Gross Pay By Dept.</td>
                <td style="padding:2px 6px;width:50px;" class="c">Avg. Gross Pay By Dept.</td>
                <td style="padding:2px 6px;">Net Pay By Dept.</td>
            </tr>
            @foreach ($data->getCountsByDiviosionDept() as $division )
            <tr>
                <?php $start = true; ?>
                <td style="padding:2px 6px;" rowspan="{{ $division->departments->count() }}" >{{ $division->div_code }} </td>
                
                @foreach ( $division->departments as $department)
                    @if (!$start)
                        <tr>
                    @endif
                        <td style="padding:2px 6px;"> {{ $department->dept_code }} </td>    
                        <td style="padding:2px 6px;text-align:right;"> {{ number_format($department->gross_total,2) }} </td>    
                        <td style="padding:2px 6px;text-align:right;"> {{ number_format(($department->gross_total/$department->bio_count),2) }} </td>    
                        <td style="padding:2px 6px;text-align:right;"> {{ number_format($department->net_pay,2) }} </td>    
                    </tr>
                    @php $start = false; 
                        $head_count += $department->bio_count; 
                        $over_all_total_gross += $department->gross_total; 
                        $over_all_total_net += $department->net_pay; 
                    @endphp
                @endforeach
            @endforeach
            <tr>
                <td colspan="2"> TOTAL </td>
                <td class="b" style="padding:2px 6px;text-align:right;"> {{ number_format($over_all_total_gross,2) }} </td>
                <td class="b" style="padding:2px 6px;text-align:right;"> </td>
                <td class="b" style="padding:2px 6px;text-align:right;"> {{ number_format($over_all_total_net,2) }} </td>

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

        

        <!-- <table style="float:left ;font-size :6pt;border-collapse:collapse;margin-top:12px;margin-left:12px" border=1>
            <table style="font-size :6pt;border-collapse:collapse;margin-top:184px;" border=1>
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
        </table> -->

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

         @if ($data->otMoreThan50hrsOriginal()->count()>0)
            <!-- <table style="width:140px;float:left;margin-left:12px;font-size :6pt;border-collapse:collapse;margin-top:12px;" border=1>
                <tr>
                    <td style="text-align:center;" colspan="2"> Overtime >= 50++ </td>
                </tr>
                @foreach ($data->otMoreThan50hrsOriginal() as $row )
                    <tr>
                        <td> {{ $row->div_code }} </td>
                        <td style="text-align:right;";>{{ $row->pax }}</td>
                    </tr>
                @endforeach
              
            </table> -->
        @endif

        <?php 
        
            $starting_margin = 6; 
            $summaryctr = 1;
        ?>

        @foreach ($data->otSummary() as $key => $value )
            @if($value > 0)
                <?php
                    $ot_data = $data->otByDeptJobtitleOriginal($key);

                    if($summaryctr == 1){
                        $starting_margin = 580; 
                    }

                    if($summaryctr == 4){
                        $starting_margin = 6; 
                    }


                ?>

                @if($summaryctr < 4)
                    <table style="width: 210px;float:left;margin-left:{{ $starting_margin }}px;font-size :6pt;border-collapse:collapse;clear:left;margin-top:12px;" border=1>
                    <?php $starting_margin += 216; ?>
                @else
                    <table style="width: 190px;float:left;margin-left:{{ $starting_margin }}px;font-size :6pt;border-collapse:collapse;margin-top:288px;clear:left;" border=1>
                    <?php $starting_margin += 204; ?>
                @endif

               
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

                <?php 
                    // $starting_margin += 204; 
                    $summaryctr++; 
                    ?>
            @endif
        @endforeach

        @if ($data->otMoreThan50hrsOriginal()->count()>0)
           
            <!-- <table style="width:140px;float:left;margin-left:{{ $starting_margin }}px;font-size :6pt;border-collapse:collapse;margin-top:284px;clear:left;" border=1>
                <tr>
                    <td style="text-align:center;" colspan="2"> Overtime >= 50++ </td>
                </tr>
                @foreach ($data->otMoreThan50hrsOriginal() as $row )
                    <tr>
                        <td> {{ $row->div_code }} </td>
                        <td style="text-align:right;";>{{ $row->pax }}</td>
                    </tr>
                @endforeach
              
            </table> -->
           
        @endif

        <table style="width:100%;margin-top:488px;font-size:8pt;" border=0>
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


