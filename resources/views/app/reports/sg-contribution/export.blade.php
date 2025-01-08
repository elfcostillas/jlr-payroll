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
        ];

    
    ?>
     <table>
        <tr>
            <td>{{ $label }}</td>
        </tr>
    </table>
    @foreach($locations as $location)
        <?php $ctr = 0;  ?>
        <table border=1 style="border-collapse:collapse;">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2" style="text-align:center;" >HDMF</td>
                <td colspan="2" style="text-align:center;" >PHIC</td>
                <td colspan="3" style="text-align:center;" >SSS</td>
            </tr>
            <tr>
                <td style="width:60px" >No</td>
                <td style="width:120px" >Dept</td>
                <td style="width:160px" >Job Title</td>
                <td style="width:260px" >Name</td>
                <td style="width:120px;text-align:center;" >EE</td>
                <td style="width:120px;text-align:center;" >ER</td>
                <td style="width:120px;text-align:center;" >EE</td>
                <td style="width:120px;text-align:center;" >ER</td>
                <td style="width:120px;text-align:center;" >EE</td>
                <td style="width:120px;text-align:center;" >ER</td>
                <td style="width:120px;text-align:center;" >EC</td>
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
                ];
            ?>
            @foreach($location->employees as $employee)
                <?php 
                    $ctr++;
                
                   
                    $over_all['hdmf_ee'] += $employee->hdmf_contri;
                    $over_all['hdmf_er'] += $employee->hdmf_contri;

                    $over_all['phic_ee'] += $employee->phil_prem;
                    $over_all['phic_er'] += $employee->phil_prem;

                    $over_all['sss_ee'] += $employee->sss_prem;
                    $over_all['sss_er'] += $employee->er_share;
                    $over_all['sss_ec'] += $employee->ec;
                    
                    /*---------------------- */

                    $per_loc['hdmf_ee'] += $employee->hdmf_contri;
                    $per_loc['hdmf_er'] += $employee->hdmf_contri;

                    $per_loc['phic_ee'] += $employee->phil_prem;
                    $per_loc['phic_er'] += $employee->phil_prem;

                    $per_loc['sss_ee'] += $employee->sss_prem;
                    $per_loc['sss_er'] += $employee->er_share;
                    $per_loc['sss_ec'] += $employee->ec;
                ?>
                
                <tr>
                    <td>{{ $ctr }}</td>
                    <td>{{ $employee->dept_code }}</td>
                    <td>{{ $employee->job_title_name }}</td>
                    <td>{{ $employee->employee_name }} </td>
                    <td>{{ $employee->hdmf_contri }}</td>
                    <td>{{ $employee->hdmf_contri }}</td>
                    <td>{{ $employee->phil_prem }}</td>   
                    <td>{{ $employee->phil_prem }}</td>   
                    <td>{{ $employee->sss_prem }}</td>   
                    <td>{{ $employee->er_share }} </td>   
                    <td>{{ $employee->ec }}</td>   
                </tr>
            @endforeach
            <tr>
                <td colspan=4 >TOTAL</td>
                <td>{{ $per_loc['hdmf_ee'] }}</td>
                <td>{{ $per_loc['hdmf_er'] }}</td>
                <td>{{ $per_loc['phic_ee'] }}</td>
                <td>{{ $per_loc['phic_er'] }}</td>
                <td>{{ $per_loc['sss_ee'] }}</td>
                <td>{{ $per_loc['sss_er'] }}</td>
                <td>{{ $per_loc['sss_ec'] }}</td>
            </tr>
        </table>
    @endforeach
    <table>
        <tr>
            <td colspan=4 >OVER ALL TOTAL</td>
            <td>{{ $over_all['hdmf_ee'] }}</td>
            <td>{{ $over_all['hdmf_er'] }}</td>
            <td>{{ $over_all['phic_ee'] }}</td>
            <td>{{ $over_all['phic_er'] }}</td>
            <td>{{ $over_all['sss_ee'] }}</td>
            <td>{{ $over_all['sss_er'] }}</td>
            <td>{{ $over_all['sss_ec'] }}</td>
        </tr>   
    </table>
</body>
</html>