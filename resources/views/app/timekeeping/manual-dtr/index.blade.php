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

    .k-calendar .k-calendar-view {
        font-size :10pt !important;
    }

    .k-calendar-td {
        font-size :9pt !important;
    }

    
</style>
@section('title')
    <h4> Manual Daily Time Record <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> </div>
                        <div class="card-body"> 
                            <div id="maingrid"></div>   
                        </div>
                    </div>
                </div>
            </div>
            <div id="pop" style="display:none;background-color:#212529;"><!--f8f9fa  #343a40 #212529 2d3035-->
                <div id="toolbar"></div>
                <div class="card card-secondary mt-1">
                    {{-- <div class="card-header"> Form </div> --}}
                    <div id="toolbar"></div>
                    <input type="hidden" id="id" data-bind="value:form.model.id" >
                    <div class="card-body">
                        <table class="formTable mb-1" border=0 style="width:100%">
                            <tr>
                                <td colspan=4>Employee <span class="require">*Required </span></td>
                                <td colspan="3"></td>
                                <td>Doc ID</td>
                            </tr>
                            <tr>
                                <td colspan=4><input type="text" id="biometric_id" data-bind="value:form.model.biometric_id"></td>
                                <td colspan=3></td>
                                <td><input type="text" id="doc_id" data-bind="value:form.model.id" readonly></td>
                            </tr>
                            <tr>
                                <td colspan=2>Date From</td>
                                <td colspan=2>Date To</td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="date_from" data-bind="value:form.model.date_from"></td>
                                <td colspan=2><input type="text" id="date_to" data-bind="value:form.model.date_to"></td>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td colspan=8>Remarks</td>
                               
                            </tr>
                            <tr>
                                <td colspan=8><input type="text" id="remarks" data-bind="value:form.model.remarks"></td>
                          </tr>
                        </table>
                        <div id="dtrgrid" ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('app.timekeeping.manual-dtr.js')
