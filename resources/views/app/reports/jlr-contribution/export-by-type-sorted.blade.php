<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php
    
        $over_all = [
            'hdmf_ee' => 0.00,
            'hdmf_er' => 0.00,

            'phic_er' => 0.00,
            'phic_ee' => 0.00,

            'sss_ee' => 0.00,
            'sss_er' => 0.00,
            'sss_ec' => 0.00,

            'sss_wisp' => 0.00,
            'mpf_er' => 0.00,
            'emp_total' => 0.00,
        ];
    ?>

    <table>
        <tr>
            <td> {{ ucfirst($src) }} </td>
        </tr>
        <tr>
            <td>
                 
                @if ($type==2)
                    PAGIBIG Contribution  
                @endif
                @if ($type==3)
                    PHIL Health Contribution
                @endif

                @if ($type==1)
                    SSS Contribution
                @endif

            </td>
        </tr>
        <tr>
            <td>{{ $label }}</td>
        </tr>
    </table>


        <?php $ctr = 0;  ?>
        <table border=1 style="border-collapse:collapse;">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
               
                @if ($type==2)
                <td>PAG IBIG No</td>
                <td colspan="2" style="text-align:center;" >HDMF</td>
                @endif
                @if ($type==3)
                <td>PHIL Health No</td>
                <td colspan="2" style="text-align:center;" >PHIC</td>
                @endif

                @if ($type==1)
                <td>SSS No</td>
                <td style="text-align:center;" >SSS</td>
                <td style="text-align:center;" >MPF</td>
                <td style="text-align:center;" >SSS</td>
                <td style="text-align:center;">MPF</td>
                @endif
                
                
               
            </tr>
            <tr>
                <td style="width:60px" >No</td>
                <td style="width:120px" >Dept</td>
                <td style="width:160px" >Job Title</td>
                <td style="width:260px" >Name</td>
                @if ($type==2)
                <td></td>
                <td style="width:120px;text-align:center;" >EE</td>
                <td style="width:120px;text-align:center;" >ER</td>
                @endif
                @if ($type==3)
                <td></td>
                <td style="width:120px;text-align:center;" >EE</td>
                <td style="width:120px;text-align:center;" >ER</td>
                @endif

                @if ($type==1)
                <td></td>
                <td style="width:120px;text-align:center;" >EE</td>
                <td style="width:120px;text-align:center;" >EE</td>

                <td style="width:120px;text-align:center;" >ER</td>
                <td style="width:120px;text-align:center;" >ER</td>

                <td style="width:120px;text-align:center;" >Total</td>
                <td style="width:120px;text-align:center;" >EC</td>

               
               
                @endif
                


              
            </tr>
            <?php
                 $per_loc = [
                    'hdmf_ee' => 0.00,
                    'hdmf_er' => 0.00,
        
                    'phic_er' => 0.00,
                    'phic_ee' => 0.00,
        
                    'sss_ee' => 0.00,
                    'sss_er' => 0.00,
                    'sss_ec' => 0.00,
                    'sss_wisp' => 0.00,
                    'mpf_er' => 0.00,
                    'emp_total' => 0.00,
                ];
            ?>
            @foreach($employees as $employee)
                <?php 
                    $ctr++;

                    $employee_total = $employee->sss_prem + $employee->sss_wisp + $employee->er_share + $employee->mpf_er;
                   
                    $over_all['hdmf_ee'] += $employee->hdmf_contri;
                    $over_all['hdmf_er'] += $employee->hdmf_contri;

                    $over_all['phic_ee'] += $employee->phil_prem;
                    $over_all['phic_er'] += $employee->phil_prem;

                    $over_all['sss_ee'] += $employee->sss_prem;
                    $over_all['sss_er'] += $employee->er_share;
                    $over_all['sss_ec'] += $employee->ec;

                    $over_all['sss_wisp'] += $employee->sss_wisp;
                    $over_all['mpf_er'] += $employee->mpf_er;

                    $over_all['emp_total'] +=  $employee_total;

                    /*---------------------- */

                    $per_loc['hdmf_ee'] += $employee->hdmf_contri;
                    $per_loc['hdmf_er'] += $employee->hdmf_contri;

                    $per_loc['phic_ee'] += $employee->phil_prem;
                    $per_loc['phic_er'] += $employee->phil_prem;

                    $per_loc['sss_ee'] += $employee->sss_prem;
                    $per_loc['sss_er'] += $employee->er_share;
                    $per_loc['sss_ec'] += $employee->ec;

                    $per_loc['sss_wisp'] += $employee->sss_wisp;
                    $per_loc['mpf_er'] += $employee->mpf_er;

                    $per_loc['emp_total'] +=  $employee_total;
                ?>
                
                <tr>
                    <td>{{ $ctr }}</td>
                    <td>{{ $employee->dept_code }}</td>
                    <td>{{ $employee->job_title_name }}</td>
                    <td>{{ $employee->employee_name }} </td>
                    @if ($type==2)
                        <td> {{ str_replace(['-',' '],"",$employee->hdmf_no) }} </td>
                        <td>{{ $employee->hdmf_contri }}</td>
                        <td>{{ $employee->hdmf_contri }}</td>
                    @endif
                    @if ($type==3)
                        <td> {{ str_replace(['-',' '],"",$employee->phic_no) }} </td>
                        <td>{{ $employee->phil_prem }}</td>   
                        <td>{{ $employee->phil_prem }}</td>   
                    @endif

                    @if ($type==1)
                        <td> {{ str_replace(['-',' '],"",$employee->sss_no) }} </td>
                       
                        <td>{{ $employee->sss_prem }}</td>   
                        <td>{{ $employee->sss_wisp }}</td>

                        <td>{{ $employee->er_share }} </td>   
                        <td>{{ $employee->mpf_er }}</td>
                        <td>{{ $employee_total  }} </td>
                        <td>{{ $employee->ec }}</td>   
                    @endif

                
                  
                  
                </tr>
            @endforeach
            <tr>
                <td colspan=4 >TOTAL</td>
              
              
             
                @if ($type==2)
                <td></td>
                <td>{{ $per_loc['hdmf_ee'] }}</td>
                <td>{{ $per_loc['hdmf_er'] }}</td>
                @endif
                @if ($type==3)
                <td></td>
                <td>{{ $per_loc['phic_ee'] }}</td>
                <td>{{ $per_loc['phic_er'] }}</td>
                @endif

                @if ($type==1)
                <td></td>
                <td>{{ $per_loc['sss_ee'] }}</td>
                <td>{{ $per_loc['sss_wisp'] }}</td>

                <td>{{ $per_loc['sss_er'] }}</td>
                <td>{{ $per_loc['mpf_er'] }}</td>

                <td>{{ $per_loc['emp_total'] }}</td>
                <td>{{ $per_loc['sss_ec'] }}</td>
                @endif
            </tr>
        </table>

    <table>
        <tr>
            <td colspan=4 >OVER ALL TOTAL</td>

                @if ($type==2)
                <td></td>
                <td>{{ $over_all['hdmf_ee'] }}</td>
                <td>{{ $over_all['hdmf_er'] }}</td>
                @endif
                @if ($type==3)
                <td></td>
                <td>{{ $over_all['phic_ee'] }}</td>
                <td>{{ $over_all['phic_er'] }}</td>
                @endif

                @if ($type==1)
                <td></td>
                <td>{{ $over_all['sss_ee'] }}</td>
                <td>{{ $over_all['sss_er'] }}</td>
              
                <td>{{ $over_all['sss_wisp'] }}</td>
                <td>{{ $over_all['mpf_er'] }}</td>
                <td>{{ $over_all['emp_total'] }} </td>
                <td>{{ $over_all['sss_ec'] }}</td>
                @endif
        
            
         
        </tr>   
    </table>
</body>
</html>