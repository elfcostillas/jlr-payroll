@extends('layouts.theme.layout')

<style>
    #viewModel {
        font-size:10pt !important;
    }

    .k-master-row {
        color : white !important;
        
    }

    .k-column-title,.k-master-row 
    {
        font-size:10pt !important;
    } 

    .k-command-cell {
        text-align: right !important;
    }
    
    .k-pager-info {
        display : block !important;
    }

    /* .card-body {
        color : black !important;
    } */

    .formTable {
        font-size: 10pt;
        color : white;
        table-layout: fixed;
        background-color: #6c757d; /*6c757d*/
    }

    .divHeader {
        background-color: #6c757d;
        padding : 8px;
        font-size : 12pt !important;
    }

    table.formTable  tr  td {
        padding : 4px;
    }

    #toolbar {
        font-size:10pt !important;
        background-color:  #6c757d !important;
    }

    .require {
        font-size : 8pt;
        color : #ffae42;
        white-space: nowrap;
    }

    .k-file-validation-message .k-text-success {
        color : #ffae42 !important;
    }

    #dtrgrid {
        font-size:10pt;
    }

    .k-icon, .k-button-text {
        font-size : 9pt !important;
    }

    .k-window-title {
        font-size : 10pt !important;
    }

    .k-footer-template td {
        padding :1px !important;
    }


</style>
@section('title')
    <h4> Manage DTR Summary <h4>
@endsection
@section('content')
    <div class="">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"><b> Payroll Period : {{ $period_obj->date_label }} </b></div>
                        <div class="card-body"> 
                            <!-- <div style ="margin-top:0px;margin-bottom:8px">
                                <input type="text"  id="fy" style="width:120px;">   
                                <a id=""> </a>  

                            
                            </div> -->
                            <div>
                                <table class="" style="width:30%;font-size:11pt;margin-bottom:8px">
                                    <tr>
                                        <td style="width:280px;padding: 6px 6px 6px 0px;">Employee Level</td>
                                        <td style="width:280px;padding: 6px 6px;">Pay Type</td>
                                        <td style="width:280px;padding: 6px 6px;"></td>  
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 6px 6px 0px;"><input type="text" id="emp_level"></td>
                                        <td style="padding: 6px 6px"><input type="text" id="pay_type"></td>
                                        <td style="padding: 6px 6px"><a id="refrehButton"> </a> </td>
                                    </tr>
                                </table>
                               
                            </div>
                            <div id="maingrid"></div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
@endsection </div>

@include('app.timekeeping.manage-dtr-summary.js')