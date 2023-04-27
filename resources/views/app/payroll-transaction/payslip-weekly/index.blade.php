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
    <h4> Payslip<h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> &nbsp; </div>
                        <div class="card-body"> 
                            <table class="formTable" border="0" style="width:100%">
                                <tr>
                                    <td width="25%">Payroll Period</td>
                                    <td width="25%">Division</td>
                                    <td width="25%">Department</td>
                                    <td width="25%">Employee</td>
                                </tr>
                                <tr>
                                    <td><input type="text" id="posted_period"> </td>
                                    <td><input type="text" id="division_id"> </td>
                                    <td><input type="text" id="department_id"> </td>
                                    <td><input type="text" id="biometric_id"> </td>
                                </tr>
                                <tr>
                                    <td colspan=4>&nbsp;</td>
                                </tr>
                            </table>
                            <div id="toolbar"></div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
@endsection

@include('app.payroll-transaction.payslip-weekly.js')