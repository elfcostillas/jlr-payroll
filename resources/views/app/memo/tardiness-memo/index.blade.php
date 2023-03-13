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
    <h4> Tardiness Memo <h4>
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
                                <td>Bio ID</td>
                                <td>Employee Name</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Date</td>
                            </tr>
                            <tr>
                                <td> <input id="biometric_id" type="text" data-bind="value:form.model.biometric_id"> </td>
                                <td colspan=3> <input id="memo_to" type="text" data-bind="value:form.model.memo_to"> </td>
                               
                                <td></td>
                                <td></td>
                                <td></td>
                                <td> <input id="memo_date" type="text"  data-bind="value:form.model.memo_date"> </td>
                            </tr>
                            <tr>
                                <td>Subject</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="8" > <input id="memo_subject" type="text"  data-bind="value:form.model.memo_subject"> </td>
                            </tr>
                            
                            <tr>
                                <td colspan="8" > <textarea id="memo_upper_body" type="text"  data-bind="value:form.model.memo_upper_body"> </textarea> </td>
                            </tr>
                            <tr>
                                <td colspan="8" > <textarea id="memo_lower_body" type="text"  data-bind="value:form.model.memo_lower_body">  </textarea> </td>
                            </tr>
                            <tr>
                                <td> Prepared By </td>
                                <td> </td>
                                <td> </td>
                                <td>Noted By </td>
                                <td> </td>
                                <td> </td>
                                <td>Noted By</td>
                                <td> </td>
                            </tr>
                            <tr>
                                <td><input id="prep_by_name" type="text" data-bind="value:form.model.prep_by_name" colspan="2"></td>
                               
                                <td></td>
                                <td><input id="noted_by_name" type="text" data-bind="value:form.model.noted_by_name" colspan="2"></td>
                                
                                <td></td>
                                <td><input id="noted_by_name_dept" type="text" data-bind="value:form.model.noted_by_name_dept" colspan="2"></td>
                                
                            </tr>
                            <tr>
                                <td><input id="prep_by_position" type="text" data-bind="value:form.model.prep_by_position" colspan=2></td>
                               
                                <td></td>
                                <td><input id="noted_by_position" type="text" data-bind="value:form.model.noted_by_position" colspan=2></td>
                                
                                <td></td>
                                <td><input id="noted_by_position_dept" type="text" data-bind="value:form.model.noted_by_position_dept" colspan=2></td>
                                
                            </tr>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('app.memo.tardiness-memo.js')
