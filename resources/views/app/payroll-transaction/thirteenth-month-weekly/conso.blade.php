<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table border=1>
        <tr>
            <td>No.</td>
            <td>Names</td>
            @foreach($months as $key => $value)
                <td> {{ $value }} </td>
            @endforeach
            <td>TOTAL</td>
            <td>NET PAY</td>
        </tr>
        @foreach($semi as $location)
            <?php
                $ctr = 1;
            ?>
            <tr>
                <td colspan=16> {{ $location->location_name  }} </td>
            </tr>
            @foreach($location->employees as $e)
                <?php
                    $total = 0;
                ?>
                <tr>
                    <td> {{ $ctr++ }} </td>
                    <td> {{ $e->lastname }}, {{ $e->firstname }} </td>
                    @foreach($months as $key => $value)
                        <td> {{ $e->basic[$key]  }} </td>
                        <?php $total +=$e->basic[$key];   ?>
                    @endforeach
                    <td> {{ $total }}</td>
                    <td> {{ round($total/12,2) }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>

    <table border=1>
        <tr>
            <td>No.</td>
            <td>Names</td>
            @foreach($months as $key => $value)
                <td> {{ $value }} </td>
            @endforeach
            <td>TOTAL</td>
            <td>NET PAY</td>
        </tr>
        @foreach($weekly as $location)
            <?php
                $ctr = 1;
            ?>
            <tr>
                <td colspan=16> {{ $location->location_name  }} </td>
            </tr>
            @foreach($location->employees as $e)
                <?php
                    $total = 0;
                ?>
                <tr>
                    <td> {{ $ctr++ }} </td>
                    <td> {{ $e->lastname }}, {{ $e->firstname }} </td>
                    @foreach($months as $key => $value)
                        <td> {{ $e->basic[$key]  }} </td>
                        <?php $total +=$e->basic[$key];   ?>
                    @endforeach
                    <td> {{ $total }}</td>
                    <td> {{ round($total/12,2) }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>
</body>
</html>