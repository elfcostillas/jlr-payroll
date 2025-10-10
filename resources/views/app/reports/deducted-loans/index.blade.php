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
    <h4> Deducted Loans<h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Employee - JLR  </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td colspan=1>Month</td>
                                    <td colspan=1>Year</td>
                                    <td colspan=2>Type</td>
                                    <td colspan=2></td>
                                    <td colspan=3></td>
                                </tr>
                                <tr>
                                    <td colspan=1><input type="text" id="payroll_period" ></td>
                                    <td colspan=1><input type="text" id="fy_year_jlr" ></td>
                                    <td colspan=2><input type="text" id="loan_tye" ></td>
                                    <td colspan=2></td>
                                    <td colspan=3></td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download_confi'><i class="fas fa-download"></i> Download (Confi)</button></td>
                                    <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download'><i class="fas fa-download"></i> Download (Rank n File)</button></td>
                                    <td colspan="4"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Employee - Support Group </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td colspan=1>Month</td>
                                    <td colspan=1>Year</td>
                                    <td colspan=2>Type</td>
                                    <td colspan=2></td>
                                    <td colspan=3></td>
                                </tr>
                                <tr>
                                    <td colspan=1><input type="text" id="payroll_period_sg" ></td>
                                    <td colspan=1><input type="text" id="fy_year_sg" ></td>
                                    <td colspan=2><input type="text" id="loan_tye_sg" ></td>
                                    <td colspan=2></td>
                                    <td colspan=3></td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download_weekly'><i class="fas fa-download"></i> Download Employee List</button></td>
                                    <td colspan="2"></td>
                                    <td colspan="4"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
           
        </div>
    </div>
@endsection

@include('app.reports.deducted-loans.js')