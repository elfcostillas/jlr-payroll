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

    #toolbar,#toolbar2,#leave_data {
        font-size:10pt !important;
        background-color:  #6c757d !important;
    }

    .require {
        font-size : 8pt;
        color : #ffae42;
        white-space: nowrap;
    }

    .k-grid td{
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    
</style>
@section('title')
    <h4> Leave Requests <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <!-- <div class="card-header"> </div> -->
                        <div class="card-body"> 
                            <div id="maingrid"></div>   
                        </div>
                    </div>
                </div>
            </div>
            <div id="pop" style="display:none;background-color:#212529;"><!--f8f9fa  #343a40 #212529 2d3035-->
                <div id="toolbar"></div>
                <div id="toolbar2"></div>
                <div class="card card-secondary mt-1">
                    {{-- <div class="card-header"> Leave Details </div> --}}
                    <form id="leaveRequestForm">
                        <input type="hidden" id="id" data-bind="value:form.model.id" >
                        <div class="card-body">
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td colspan=2>Employee Name <span class="require">*Required </span></td>
                                    <td colspan=2>Division <span class="require"> </span></td>
                                    <td colspan=2>Department</td>
                                    <td>Position</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan=2><input type="text" id="biometric_id" data-bind="value:form.model.biometric_id"></td>
                                    <td colspan=2><input type="text" id="division_desc" data-bind="value:employee.division_desc"></td>
                                    <td colspan=2><input type="text" id="department_desc" data-bind="value:employee.department_desc"></td>
                                    <td colspan=2><input type="text" id="job_title_desc" data-bind="value:employee.job_title_desc"></td>
                                </tr>
                                {{-- <tr>
                                    <td colspan=4>Address (Primary)</td>
                                    <td colspan=4>Address (Secondary)</td>
                                </tr>
                                <tr>
                                    <td colspan=4><input type="text" id="primary_addr" data-bind="value:form.model.primary_addr"></td>
                                    <td colspan=4><input type="text" id="secondary_addr" data-bind="value:form.model.secondary_addr"></td>
                                </tr> --}}
                                <tr>
                                    <td>Reliever</td>
                                    <td></td>
                                    <td>Date From</td>
                                    <td></td>
                                    <td>Date To</td>
                                    <td></td>
                                    <td>Type Of Leave</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan=2><input type="text" id="reliever_id" data-bind="value:form.model.reliever_id"></td>
                                    <td colspan=2><input type="text" id="date_from" data-bind="value:form.model.date_from" required></td>
                                    <td colspan=2><input type="text" id="date_to" data-bind="value:form.model.date_to" required></td>
                                    <td colspan=2><input type="text" id="leave_type" data-bind="value:form.model.leave_type" required></td>
                                </tr>
                                <tr>
                                    <td colspan=8>Reason :</td>
                                </tr>
                                <tr>
                                    <td colspan=8><input type="text" id="remarks" data-bind="value:form.model.remarks"></td>
                                </tr>
                            </table>

                            <div id="subgrid"></div>
                            <div id="leave_data" style="margin-top:2px">
                               
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            {{-- <div id="pop_sub"  style="display: none;"> <div id="leave_data" ></div> </div> --}}
        </div>
    </div>
@endsection

@include('app.accounts.leave-request.js')
