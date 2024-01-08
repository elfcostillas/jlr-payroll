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
    <h4> Manage DTR - Weekly <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header"> Payroll Period </div>
                        <div class="card-body"> 
                            <div id="maingrid"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header"> Employee List </div>
                        <div class="card-body"> 
                            <div id="subgrid"></div>
                        </div>
                    </div>
                </div>
                <div id="pop" style="display:none;">
                    <table style="width:100%">
                        <tr>
                            <td>
                                <div style="width:1084px;" id="dtrgrid"></div>
                            </td>
                            <td style="width:170px;background-color: #f8f9fa;vertical-align:top;">
                                <div style="height:300px;overflow:scrollable;" id="raw-logs"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection </div>

@include('app.timekeeping.manage-dtr-weekly.js')