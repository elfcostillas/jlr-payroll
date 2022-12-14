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

    #toolbar,#toolbar2 {
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

    .k-grid-save-changes {
        font-size : 10pt !important;
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
                    <div class="card-header"> Leave Details </div>
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
                                    <td colspan=2><input type="text" id="leave_type" data-bind="value:form.model.leave_type"></td>
                                </tr>
                                <tr>
                                    <td colspan=8>Reason :</td>
                                </tr>
                                <tr>
                                    <td colspan=8><input type="text" id="remarks" data-bind="value:form.model.remarks"></td>
                                </tr>
                            </table>

                            <div id="subgrid"></div>
                        </div>
                    </form>
                </div>

                {{-- <div class="card card-secondary mt-1">
                    <div class="card-header"> Government ID & Deductions </div>
                    <div class="card-body">
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td>SSS No</td>
                                <td>Deduct SSS</td>
                                <td>PHIC</td>
                                <td>Deduct PHIC</td>
                                <td>HDMF</td>
                                <td>HDMF Contri.</td>
                                <td>T.I.N.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type="text" id="sss_no" data-bind="value:form.model.sss_no"></td>
                                <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.deduct_sss" id="deduct_sss"></div></div></td>
                                <td><input type="text" id="phic_no" data-bind="value:form.model.phic_no"></td>
                                <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.deduct_phic" id="deduct_phic"></div></div></td>
                                <td><input type="text" id="hdmf_no" data-bind="value:form.model.hdmf_no"></td>
                                <td><input type="text" id="hdmf_contri" data-bind="value:form.model.hdmf_contri"></td>
                                <td colspan=2><input type="text" id="tin_no" data-bind="value:form.model.tin_no"></td>
                               
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card card-secondary mt-1">
                    <div class="card-header"> Employee Information </div>
                    <div class="card-body">
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td>Biometric ID</td>
                                <td>Location</td>
                                <td colspan=2>Division</td>
                                <td colspan=2>Department</td>
                                <td colspan=2>Job Title</td>
                            </tr>
                            <tr>
                                <td colspan="1"><input class="formTable" type="text" id="biometric_id" data-bind="value:form.model.biometric_id"></td>
                                <td><input type="text" id="location_id" data-bind="value:form.model.location_id"></td>
                                <td colspan=2><input type="text" id="division_id" data-bind="value:form.model.division_id"></td>
                                <td colspan=2><input type="text" id="dept_id" data-bind="value:form.model.dept_id"></td>
                                <td colspan=2><input type="text" id="job_title_id" data-bind="value:form.model.job_title_id"></td>
                            </tr>
                            <tr>
                                <td colspan=2>Employment Status</td>
                                <td colspan=2>Exit Status</td>
                                <td colspan=2>Employee Type</td>
                                <td>Date Hired</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="employee_stat" data-bind="value:form.model.employee_stat"></td>
                                <td colspan=2><input type="text" id="exit_status" data-bind="value:form.model.exit_status"></td>
                                <td colspan=2><input type="text" id="pay_type" data-bind="value:form.model.pay_type"></td>
                                <td colspan=2><input type="text" id="date_hired" data-bind="value:form.model.date_hired"></td>
                            </tr>
                            <tr>
                                <td colspan=2>Level</td>
                                <td colspan=2></td>
                                <td colspan=2></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="emp_level" data-bind="value:form.model.emp_level"></td>
                                <td colspan=2></td>
                                <td colspan=2></td>
                                <td colspan=2></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card card-secondary mt-1">
                    <div class="card-header"> Compensation and Benefits </div>
                    <div class="card-body">
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td colspan=2>Basic Salary </td>
                                <td>Daily Rate</td>
                                <td></td>
                                <td colspan=2></td>
                                <td colspan=2></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="basic_salary" data-bind="value:form.model.basic_salary"></td>
                                <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.is_daily" id="is_daily"></div></div></td>
                                <td></td>
                                <td colspan=2></td>
                                <td colspan=2></td>
                            </tr>
                         
                        </table>
                    </div>
                </div> --}}

                
            </div>
        </div>
    </div>
@endsection

@include('app.timekeeping.leave-absence.js')
