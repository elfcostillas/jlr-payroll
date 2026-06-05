<?php

use Carbon\Carbon;

function dformat($date)
{
    return Carbon::createFromFormat('Y-m-d',$date)->format('M d');
}

function nformat($n)
{
    return ($n>0) ? number_format($n,2) : '';
}

?>

<script>
    $(document).ready(function(){
        $("table#rowClick tr").click(function(){
            $(this).toggleClass("active");
    
        });

        $(".editable").on('change',function(e){
            
            let val = this.value.replace(/\,/g,''); 

            // console.log(val,this.id);
            console.log(val == null,val);
            if(val != null && val != 'null' && val != '') {
                $.post('thirteenth-month-confi/insert-or-update', {
                    id :this.id,
                    val : val
                }, function($result){

                });
            }
        });
    });
</script>
<style>
    .p02 { padding : 0px 6px; }
    .t_header { font-weight: bold; }
    .r-align { text-align : right;}
    .c-align { text-align : center;}

    .active {
        background-color: #90e0ef;
    }

    table tr.active {background: #90e0ef;}

    tbody th {
        position: -webkit-sticky; /* for Safari */
        position: sticky;
        left: 0;
        background: white; /* dont remove */
        /* border-right: 1px solid #CCC; */
        vertical-align: middle;
        
    }

    .editable {
        border : 2px solid #79D021
    }

    .manual {
        border : 2px solid orange;
    }

    .noteditable
    {

    }

    .inputfield {
        width : 80px;
        margin : 4px;
        text-align:right;
        font-family : 'Arial';
        font-size : 10pt;
    }
</style>

<table id="rowClick" border=1 style="font-size : 8pt;">
    <tr>
        <td class="p02 t_header c-align" style="min-width: 164px;">Employee Name</td>

        @foreach ($data['payroll_periods'] as $period)
            <td class="p02 t_header c-align" style="min-width: 86px;">
                {{ $period->label }}
            </td>
        @endforeach
        <td class="p02 t_header c-align" style="min-width: 86px;"> Gross Pay </td>
        <td class="p02 t_header c-align" style="min-width: 86px;"> Net Pay </td>
    </tr>

    @foreach ($data['employees'] as $employee)
        <tr>
            <th  class="p02"> {{ $employee->thirteenth_pay->getName() }}</th>
            @foreach ($data['payroll_periods'] as $iPeriod)
              
                <td  class="p02 r-align"> 
                       <input class="inputfield editable {{ $employee->thirteenth_pay->getBasicPay($iPeriod->id)['tag'] }}" id="{{$employee->thirteenth_pay->getBiometricID()}}|{{$iPeriod->id}}" 
                        type="text" 
                        value="{{ nformat($employee->thirteenth_pay->getBasicPay($iPeriod->id)['value']) }}"> 
                </td>
            @endforeach
            <td  class="p02 r-align"> {{ nformat($employee->thirteenth_pay->getGrossPay()) }}</td>
            <td  class="p02 r-align"> {{ nformat($employee->thirteenth_pay->getNetPay()) }}</td>
        </tr>
    @endforeach
</table>

