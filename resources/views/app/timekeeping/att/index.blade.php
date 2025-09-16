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
    <h4> ATT Logs <h4>
@endsection
@section('content')
    <div class="">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-secondary">
                        <div class="card-header"> &nbsp; </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td>Date From</td>
                                    <td>Date To</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                  
                                    
                                </tr>
                                <tr>
                                    <td> <input type="text" name="" id="date_from"> </td>
                                    <td> <input type="text" name="" id="date_to"> </td>
                                    <td> </td>
                                    <td></td>
                                    <td><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download'><i class="fas fa-download"></i> Download Logs</button></td>
                                </tr>
                              
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection </div>

@include('app.timekeeping.att.js')