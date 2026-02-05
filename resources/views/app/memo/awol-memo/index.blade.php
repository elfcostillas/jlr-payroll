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
    <h4> Absent Without Official Leave Memo <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> </div>
                        <div class="card-body"> 
                            <table style="width:60%">
                                <tr>
                                    <td style="width:25%"><input id="memo_month" type="text"  data-bind="value:form.model.memo_month"></td>
                                    <td style="width:25%"><input id="memo_year" type="text"  data-bind="value:form.model.memo_year"></td>
                                    <td style="width:25%"> <button type="button" style="margin : 0 auto;width:120px" data-bind="click:buttonHandler.reload" class="btn btn-block btn-secondary btn-sm"> <i class="fas fa-sync"></i> Refresh List  </button> </td>
                                    <td style="width:25%"> <button type="button" style="margin : 0 auto;width:140px" data-bind="click:buttonHandler.regroup" class="btn btn-block btn-secondary btn-sm"> <i class="fas fa-chart-pie"></i> Regroup Awol  </button> </td>
                                   
                                </tr>
                            </table>
                            <div style="margin-top:8px;" id="maingrid"></div>   
                                
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@include('app.memo.awol-memo.js')
