<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
    $label_emp_type = null;

    $month = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    switch($array['loan_type']) {
        case 1 ;
        case 2 ;
            $label_no = 'HDMF No.';
            break;

        case 3 ;
        case 4 ;
            $label_no = 'SSS No.';
            break;
    }

    switch($array['emp_type']) {
            case 'sg' :
                    $label_emp_type = 'Support Group';
                break;

            case 'confi' :
                    $label_emp_type = 'Confi Employee';
                break;

            case 'semi' :
                    $label_emp_type = 'Semi monthly';
                break;
    }

    $counter = 1;
    $over_all = 0;
?>
<body>
    <table border=1 style="border-collapse: collapse;">
        <tr>
            <td> {{ $label_emp_type }} </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>{{ $label->description }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>{{ $month[$array['month']] }} {{ $array['year'] }} </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>No</td>
            <td>Name</td>
            <td>{{ $label_no }}</td>
            <td>Amount</td>
            <td></td>
        </tr>
        @foreach ($data as $row)
            <tr>
                <td>{{ $counter++ }}</td>
                <td>{{ $row->lastname }} , {{ $row->firstname }}</td>
                <td>
                    <?php
                        switch($array['loan_type']) {
                            case 1 ;
                            case 2 ;
                                echo $row->hdmf_no;
                                break;

                            case 3 ;
                            case 4 ;
                                echo $row->sss_no;
                                break;
                        }
                    ?>
                </td>
                <td>{{ $row->amount }}</td>
                <td></td>
            </tr>
            <?php
                $over_all += $row->amount;
            ?>
        @endforeach
        <tr>
            <td> OVER ALL TOTAL</td>
            <td> </td>
            <td> </td>
            <td>{{ $over_all }} </td>
            <td> </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td>REMARKS</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
         <tr>
            <td>ADD</td>
            <td>None</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
         <tr>
            <td>DELETE</td>
            <td>None</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</body>
</html>
