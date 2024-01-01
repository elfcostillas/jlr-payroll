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

    .k-grid table {
        table-layout: fixed;
    }

    .formTable {
        font-size: 10pt;
        color : white;
        table-layout: fixed;
        background-color: #6c757d; /*6c757d*/
    }
    
    table.formTable  tr  td {
        padding : 4px;
    }

    #toolbar,#toolbar2 {
        font-size:10pt !important;
        background-color:  #6c757d !important;
    }

    .k-grid td{
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    
</style>
@section('title')
    <h4> Failure to Punch <h4>
@endsection
@section('content')
    {{-- <div class="container"> --}}
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        {{-- <div class="card-header"> </div> --}}
                        <div class="card-body"> 
                            <div style="width: 100%" id="maingrid"></div>
                        </div>
                    </div>
                </div>
              
            </div>

            <div id="pop" style="display:none;background-color:#212529;"><!--f8f9fa  #343a40 #212529 2d3035-->
                <div id="toolbar"></div>
                <div id="toolbar2"></div>
                <div class="card card-secondary mt-1">
                   
                    <input type="hidden" id="id" data-bind="value:form.model.id" >
                    <div class="card-body">
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td colspan=2>Employee</td>
                                <td colspan=3></td>
                                <td >FTP Date</td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="biometric_id" data-bind="value:form.model.biometric_id"></td>
                                <td colspan=3></td>
                                <td colspan=1><input type="text" id="ftp_date" data-bind="value:form.model.ftp_date"></td>
                                
                            </tr>
                            <tr>
                                <td colspan=2 > FTP Type </td>
                                <td>Time In</td>
                                <td>Time Out</td>
                                <td>Over Time In</td>
                                <td>Over Time Out</td>
                            </tr>
                            <tr>
                                <td colspan=2 ><input type="text" id="ftp_type"  data-bind="value:form.model.ftp_type"></td>
                                <td> <input type="text" id="time_in" data-bind="value:form.model.time_in"> </td>
                                <td> <input type="text" id="time_out" data-bind="value:form.model.time_out"> </td>
                                <td> <input type="text" id="ot_in" data-bind="value:form.model.ot_in"> </td>
                                <td> <input type="text" id="ot_out" data-bind="value:form.model.ot_out"> </td>
                            </tr>
                            <tr>
                                <td colspan="6" >Reason :</td>
                            </tr>
                            <tr>
                                <td colspan="6"> <input type="text" id="ftp_reason" data-bind="value:form.model.ftp_reason"> </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}
@endsection

@include('app.timekeeping.ftp.js')