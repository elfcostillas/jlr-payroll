<?php
    function nformat($n)
    {
        if($n>8){
            return number_format($n,0);
        }
        else if($n >0 && $n<=8)
        {
            return round($n/8,1);
        }else{
            return '';
        }
    }

   
?>
<style>
    * {
        font-family: 'Consolas';
        font-size: 10pt;
    }

    table tr td {
        padding : 4px;
    }
</style>


    {{-- <tr>
        <td>Biometric ID</td>
        <td>Name</td>
        <td>Count</td>
       
    </tr> --}}
    @if($data)
    
        @foreach($data as $div)
            <table style="border-collapse:collapse;" border=1>
                <tr>
                    <td colspan=4>{{ $div->div_name }}</td>
                    
                </tr>
                <tr>
                    <td>No.</td>
                    <td>Biometric ID</td>
                    <td>Employee Name</td>
                    <td>Frequency</td>
                </tr>
                @php  $ctr = 1;  @endphp
            
                @foreach($div->emp as $e)
                    <tr>
                        <td style="min-width:40px">{{ $ctr++ }}</td>
                        <td style="min-width:55px">{{ $e->biometric_id }}</td>
                        <td style="min-width:330px" >{{ $e->employee_name }}</td>
                        <td style="text-align:center;min-width:40px">{{ $e->late_count }}</td>
                    </tr>
                @endforeach
            </table>
            <br>
        @endforeach
            
    <br>            
    @endif
    

