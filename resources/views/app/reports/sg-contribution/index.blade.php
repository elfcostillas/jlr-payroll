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
    <h4>Attendance Reports<h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Cumulative </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td>Month</td>
                                    <td>Year</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td> <input type="text" id= "scripts_months" /></td>
                                    <td><input type="text" id= "scripts_year" /></td>
                                    <td></td>
                                    <td></td>
                                    <td><button type="button" class="btn btn-block btn-primary btn-sm" data-bind='click:buttonHandler.web'><i class="fas fa-table"></i> Generate (Web)</button></td>
                                    <td><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download'><i class="fas fa-table"></i> Download</button></td>
                                  
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Contribution By Type </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td>Month</td>
                                    <td>Year</td>
                                    <td>Type</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td> <input type="text" id= "scripts_months2" /></td>
                                    <td> <input type="text" id= "scripts_year2" /></td>
                                    <td> <input type="text" id= "scripts_type2" /></td>
                                    <td></td>
                                    <td><button type="button" class="btn btn-block btn-primary btn-sm" data-bind='click:buttonHandler.web_2'><i class="fas fa-table"></i> Generate (Web)</button></td>
                                    <td><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download_2'><i class="fas fa-table"></i> Download</button></td>
                                  
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>  
            
            
            

        </div>
    </div>
@endsection

@include('app.reports.sg-contribution.js')