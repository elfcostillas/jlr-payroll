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
    <h4> Q.P.I.P.<h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Support Group </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td>Date From</td>
                                    <td>Date To</td>
                                    <!-- <td style="text-align:center;">More than 72 hrs</td> -->
                                    <td style="text-align:center;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                </tr>
                                <tr>
                                    <td> <input type="text" name="" id="date_from2"  > </td>
                                    <td> <input type="text" name="" id="date_to2" > </td>
                                    <!-- <td style="text-align:center;" > <input type="checkbox" name="" id="isMoreThan"> </td> -->
                                    <td></td>
                                    <td></td>
                                    
                                    <td><button type="button" class="btn btn-block btn-primary btn-sm" data-bind='click:buttonHandler.viewSummary'><i class="fas fa-table"></i> View Page</button></td>
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
                        <div class="card-header"> Rank and File </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td>Date From</td>
                                    <td>Date To</td>
                                    <!-- <td style="text-align:center;">More than 72 hrs</td> -->
                                    <td style="text-align:center;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                </tr>
                                <tr>
                                    <td> <input type="text" name="" id="date_from2"  > </td>
                                    <td> <input type="text" name="" id="date_to2" > </td>
                                    <!-- <td style="text-align:center;" > <input type="checkbox" name="" id="isMoreThan"> </td> -->
                                    <td></td>
                                    <td></td>
                                    
                                    <td><button type="button" class="btn btn-block btn-primary btn-sm" data-bind='click:buttonHandler.viewSummary'><i class="fas fa-table"></i> View Page</button></td>
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
                        <div class="card-header"> Supervisors and Managers </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td>Date From</td>
                                    <td>Date To</td>
                                    <!-- <td style="text-align:center;">More than 72 hrs</td> -->
                                    <td style="text-align:center;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                </tr>
                                <tr>
                                    <td> <input type="text" name="" id="date_from2"  > </td>
                                    <td> <input type="text" name="" id="date_to2" > </td>
                                    <!-- <td style="text-align:center;" > <input type="checkbox" name="" id="isMoreThan"> </td> -->
                                    <td></td>
                                    
                                    <td></td>
                                    <td><button type="button" class="btn btn-block btn-primary btn-sm" data-bind='click:buttonHandler.viewSummary'><i class="fas fa-table"></i> View Page</button></td>
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

@include('app.reports.qpip.js')