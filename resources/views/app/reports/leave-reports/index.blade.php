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

    
</style>
@section('title')
    <h4> Reports<h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Employee Leaves </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td>Date From</td>
                                    <td>Date To</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                </tr>
                                <tr>
                                    <td> <input type="text" name="" id="date_from"> </td>
                                    <td> <input type="text" name="" id="date_to"> </td>
                                    <td><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.summarize'><i class="fas fa-download"></i> Summary</button></td>
                                    <td><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.leaveByEmployee'><i class="fas fa-download"></i> Sort By Employee</button></td>
                                    <td><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download'><i class="fas fa-download"></i> Download Excel</button></td>
                                    <td><button type="button" class="btn btn-block btn-primary btn-sm" data-bind='click:buttonHandler.view'><i class="fas fa-table"></i> View Page</button></td>
                                    <td><button type="button" class="btn btn-block btn-warning btn-sm" data-bind='click:buttonHandler.leaveByEmployeeConfi'><i class="fas fa-table"></i> Confi </button></td>
                                </tr>
                                <tr>
                                    <td colspan=2></td>
                                    <td> <button type=button class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.dailyMonthly'> Daily / Monthly  </button> </td>
                                </tr>
                              
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Employee Leaves (By Date) </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td>Date</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                </tr>
                                <tr>
                                    <td> <input type="text" name="" id="on_date"> </td>
                                    <td> </td>
                                    <td><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.onThatDay'><i class="fas fa-download"></i> Generate</button></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>          
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Q P I P</div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td>Date From</td>
                                    <td>Date To</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                </tr>
                                <tr>
                                    <td> <input type="text" name="" id="date_from2"> </td>
                                    <td> <input type="text" name="" id="date_to2"> </td>
                                    <td><button type="button" class="btn btn-block btn-primary btn-sm" data-bind='click:buttonHandler.viewKPI'><i class="fas fa-table"></i> View Page</button></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                              
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('app.reports.leave-reports.js')