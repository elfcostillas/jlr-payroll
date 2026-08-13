<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @page{
            margin : 24px;
        }

        * {
            font-family:  sans-serif;
            font-size: 8pt;
        }

        table tr td {
            padding : 3px;
            min-width: 45px;
        }
    </style>

    <?php
        function number_formatter($n){
            if($n > 0){
                return number_format($n,0);
            }else{
                return '';
            }
                
        }

        function get_total_rnf($half,$departments)
        {
            $total = 0;

            foreach($departments as $department)
            {
                if($half == 1)
                {
                    $total += $department->man_hours_1sthalf_rnf;
                }else{
                    $total += $department->man_hours_2ndhalf_rnf;
                }
            }

            return $total;
        }

        function get_total_confi($half,$departments)
        {
            $total = 0;

            foreach($departments as $department)
            {
                if($half == 1)
                {
                    $total += $department->man_hours_1sthalf_confi;
                }else{
                    $total += $department->man_hours_2ndthalf_confi;
                }
            }

            return $total;
        }

        function get_overall_total($departments)
        {
            $total = 0;
            
            $total  = get_total_confi(1,$departments) + get_total_confi(2,$departments) + get_total_rnf(1,$departments) + get_total_rnf(2,$departments);

            return $total;
        }

        function getTotalperGender($gender,$data)
        {
            $total = 0;
           
            foreach($data as $division)
            {
                foreach($division->departments as $department)
                {
                    if($gender == 'M'){
                        $total += $department->male_2ndhalf;
                    }else{
                        $total += $department->female_2ndhalf;
                    }
                }
            }

            return $total;
        }

        function getTotalBothGender($data)
        {
             $total = 0;
           
            foreach($data as $division)
            {
                foreach($division->departments as $department)
                {
                    $total += ($department->male_2ndhalf + $department->female_2ndhalf);
                }
            }

            return $total;
        }

        function getTotala($data,$cut_off,$emp_level)
        {
            $total = 0;
           
            foreach($data as $division)
            {
                foreach($division->departments as $department)
                {
                    if($cut_off == 1){
                        if($emp_level == 'rnf'){
                            $total += $department->man_hours_1sthalf_rnf;
                        }else {
                            $total += $department->man_hours_1sthalf_confi;
                        }
                    }else {
                        if($emp_level == 'rnf'){
                            $total += $department->man_hours_2ndhalf_rnf;
                        }else {
                            $total += $department->man_hours_2ndthalf_confi;
                        }
                    }
                }
            }

            return $total;
        }

        function getOverAllTotala($data)
        {
            $total = 0;
           
            foreach($data as $division)
            {
                $total += get_total_confi(1,$division->departments) + get_total_confi(2,$division->departments) + get_total_rnf(1,$division->departments) + get_total_rnf(2,$division->departments);
                

                // foreach($division->departments as $department)
                // {

                //     $total  = get_total_confi(1,$departments) + get_total_confi(2,$departments) + get_total_rnf(1,$departments) + get_total_rnf(2,$departments);
                     
                // }
            }

            return $total;
        }

    ?>
</head>
<body>
    
    <table border=1 style="width:100%;border-collapse: collapse;">
        <tr>
            <td style="text-align:center;" rowspan="3">Division</td>
            <td style="text-align:center;" rowspan="3">Department</td>
            <td style="text-align:center;" colspan="8">Man Hours</td>
            <td style="text-align:center;" rowspan="3" > Total</td>
            <td style="text-align:center;" colspan="3" rowspan="2" > Sex</td>

        </tr>
        <tr>
            <td style="text-align:center;" colspan=4>1st half</td>
            <!-- <td colspan=2>Sex</td> -->
            <td style="text-align:center;" colspan=4>2nd half</td>
         
         
        </tr>
        <tr>
            <td style="text-align:center;"> R & F</td>
            <td style="text-align:center;">Total <br> R & F</td>
            <td style="text-align:center;">Confi</td>  
            <td style="text-align:center;">Total <br> Confi</td>  
            <!-- <td>M</td>
            <td>F</td> -->
            <td style="text-align:center;"> R& F</td>
            <td style="text-align:center;">Total <br> R & F</td>
            <td style="text-align:center;">Confi</td>
            <td style="text-align:center;">Total <br> Confi</td>  
            <td style="text-align:center;">M</td>
            <td style="text-align:center;">F</td>
            <td style="text-align:center;">Total</td>
           
        </tr>
        @foreach ($data as $division)
            @foreach ($division->departments as $department)
                <tr>
                    @if($loop->iteration == 1)
                        <td rowspan="{{ count($division->departments) }}">
                            {{ $division->div_code }}
                        </td>
                    @endif

                    <td>{{ $department->dept_code }}</td>

                    <td style="text-align: right;"> {{ number_formatter($department->man_hours_1sthalf_rnf) }} </td>
                    @if($loop->iteration == 1)
                        <td style="text-align: right;font-weight:bold;" rowspan="{{ count($division->departments) }}">
                            {{ number_formatter(get_total_rnf(1,$division->departments)) }}
                        </td>
                    @endif
                    <td style="text-align: right;"> {{ number_formatter($department->man_hours_1sthalf_confi) }} </td>
                    @if($loop->iteration == 1)
                        <td style="text-align: right;font-weight:bold;" rowspan="{{ count($division->departments) }}">
                            {{ number_formatter(get_total_confi(1,$division->departments)) }}
                        </td>
                    @endif
                    <!-- <td style="text-align: right;"> {{ number_formatter($department->male_1sthalf) }} </td>
                    <td style="text-align: right;"> {{ number_formatter($department->female_1sthalf) }} </td> -->
                    <td style="text-align: right;"> {{ number_formatter($department->man_hours_2ndhalf_rnf) }} </td>
                    @if($loop->iteration == 1)
                        <td style="text-align: right;font-weight:bold;" rowspan="{{ count($division->departments) }}">
                            {{ number_formatter(get_total_rnf(2,$division->departments)) }}
                        </td>
                    @endif
                    <td style="text-align: right;"> {{ number_formatter($department->man_hours_2ndthalf_confi) }} </td>
                    @if($loop->iteration == 1)
                        <td style="text-align: right;font-weight:bold;" rowspan="{{ count($division->departments) }}">
                            {{ number_formatter(get_total_confi(2,$division->departments)) }}
                        </td>
                    @endif

                    @if($loop->iteration == 1)
                        <td style="text-align: right;font-weight:bold;" rowspan="{{ count($division->departments) }}">
                            {{ number_formatter(get_overall_total($division->departments)) }}
                        </td>
                    @endif
                    
                    <td style="text-align: right;"> {{ number_formatter($department->male_2ndhalf) }} </td>
                    <td style="text-align: right;"> {{ number_formatter($department->female_2ndhalf) }} </td>
                    <td style="text-align: right;font-weight:bold;"> {{ number_formatter($department->male_2ndhalf + $department->female_2ndhalf) }}  </td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="2" style="text-align: center;font-weight:bold;">TOTAL</td>
            
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotala($data,1,'rnf')) }}</td> 
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotala($data,1,'rnf')) }}</td>
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotala($data,1,'confi')) }}</td>
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotala($data,1,'confi')) }}</td>
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotala($data,2,'rnf')) }}</td> 
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotala($data,2,'rnf')) }}</td>
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotala($data,2,'confi')) }}</td>
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotala($data,2,'confi')) }}</td>
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getOverAllTotala($data)) }}</td>
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotalperGender('M',$data)) }}</td>
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotalperGender('F',$data)) }}</td>
            <td style="text-align: right;font-weight:bold;">{{ number_formatter(getTotalBothGender($data)) }}</td>
        </tr>

      
    </table>
</body>
</html>