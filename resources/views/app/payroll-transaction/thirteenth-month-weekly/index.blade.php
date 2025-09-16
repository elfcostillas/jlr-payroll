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

    button.fixwidth {
        width: 120px;
    }


</style>
@section('title')
    <h4> 13th Month Pay <h4>
@endsection
@section('content')
    <div class="container_no" style="margin : 4px">
        <div id="viewModel" >
            <div class="row">
             
                <div id="toolbar" style="width:100%" ></div>
            </div>
            <div class="row mt-2">
                <div style="overflow:scroll;width :100%;height:580px;">
                    <div id="resultTable" ></div>
                </div>
            </div>
            <div id="pop" style="display:none;">
                <table class="formTable" style="width:100%" border=1>
                    <tr>
                        <td>Year</td>
                    </tr>
                    <tr>
                        <td><input id="popyear" type="text"></td>
                    </tr>
                    <tr>
                        <td>Location</td>
                    </tr>
                    <tr>
                        <td><input id="ddLocation" type="text"></td>
                    </tr>
                    <tr>
                        <td> <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : handler.print"> Print</button> </td>
                                        
                    </tr>
                </table>
            </div>
        </div>

        
    </div>

@endsection

@include('app.payroll-transaction.thirteenth-month-weekly.js')

