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
    <h4> Biometric<h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-secondary">
                        <div class="card-header"> Biometric ID </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td colspan=3><input type="text" id="biometric_id" data-bind="value:form.model.biometric_id" ></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td ><button type="button" class="btn btn-block btn-primary btn-sm" data-bind='click:buttonHandler.save'><i class="fas fa-save"></i> Save</button></td>
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

@include('app.accounts.biometric.js')