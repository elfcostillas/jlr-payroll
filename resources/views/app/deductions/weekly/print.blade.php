<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen Deductions</title>

    <style>
        * {
            font-size : 9pt;
        }
        td {
            padding : 2px 4px;
        }

        .b {
            font-weight:bold;
        }

        .c {
            text-align:center;
        }

        .r {
            text-align:right;
        }

        @page { margin: 40px 40px 60px 40px; }

        main {
            page-break-inside: auto;
        }

        body {
            margin-top: 28px;
            

        } 

    </style>
</head>
<body>
    <header>

    </header>
    <?php
        function formatNum($n)
        {
            if($n>0){
                return number_format($n,2);
            }else{
                return '';
            }
        }

        $total_bpn = 0;
        $total_bps = 0;
        $total_agg = 0;
        $total_all = 0;

    ?>
    <main>
        <div style="margin-bottom : 12px;font-size:12pt" class="b">Canteen Deduction ({{$label}})</div>
        <table border=1 style="border-collapse:collapse">
            <thead>
                <tr>
                    <td class="b">Employee Name</td>
                    <td class="b c"style="width:84px;">BPN (Canteen)</td>
                    <td class="b c"style="width:84px;">BPS (Canteen)</td>
                    <td class="b c"style="width:84px;">AGG (Canteen)</td>
                    <td class="b c"style="width:84px;">TOTAL</td>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td > {{ $row->employee_name }}</td>
                        <td class="r"> {{ formatNum($row->canteen_bpn) }}</td>
                        <td class="r"> {{ formatNum($row->canteen_bps) }}</td>
                        <td class="r"> {{ formatNum($row->canteen_agg) }}</td>
                        <td class="r">  {{ formatNum($row->canteen) }}</td>
                    </tr>

                    <?php
                    $total_bpn += $row->canteen_bpn;
                    $total_bps += $row->canteen_bps;
                    $total_agg += $row->canteen_agg;
                    $total_all += $row->canteen;
                    
                    ?>
                @endforeach
                <tr>
                    <td class="b">TOTALS :</td>
                    <td class="b r"style="width:84px;"> {{ formatNum($total_bpn) }}</td>
                    <td class="b r"style="width:84px;"> {{ formatNum($total_bps) }}</td>
                    <td class="b r"style="width:84px;"> {{ formatNum($total_agg) }}</td>
                    <td class="b r"style="width:84px;"> {{ formatNum($total_all) }}</td>
                </tr>
            </tbody>
        </table>
        
    </main>
</body>
</html>