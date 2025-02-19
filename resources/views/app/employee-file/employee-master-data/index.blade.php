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
    <h4> Employee Data <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> </div>
                        <div class="card-body"> 
                            <input type="text" id="employee_search" style="margin-bottom : 4px" placeholder="Search name here...">
                            <div id="maingrid"></div>   
                        </div>
                    </div>
                </div>
            </div>
            <div id="ratePop" style="display:none ;background-color:#212529;">
                <div id="ratesTable"></div>
            </div>

            <div id="pop" style="display:none;background-color:#212529;"><!--f8f9fa  #343a40 #212529 2d3035-->
                <div id="toolbar"></div>
                <div class="card card-secondary mt-1">
                    <div class="card-header"> Basic Information </div>
                    <input type="hidden" id="id" data-bind="value:form.model.id" >
                    <div class="card-body">
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td colspan=2>Lastname <span class="require">*Required </span></td>
                                <td colspan=2>Firstname <span class="require">*Required </span></td>
                                <td colspan=2>Middlename</td>
                                <td>Suffix(Jr/Sr.)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="lastname" data-bind="value:form.model.lastname"></td>
                                <td colspan=2><input type="text" id="firstname" data-bind="value:form.model.firstname"></td>
                                <td colspan=2><input type="text" id="middlename" data-bind="value:form.model.middlename"></td>
                                <td colspan=2><input type="text" id="suffixname" data-bind="value:form.model.suffixname"></td>
                            </tr>
                            <tr>
                                <td colspan=4>Address (Primary)</td>
                                <td colspan=4>Address (Secondary)</td>
                            </tr>
                            <tr>
                                <td colspan=4><input type="text" id="primary_addr" data-bind="value:form.model.primary_addr"></td>
                                <td colspan=4><input type="text" id="secondary_addr" data-bind="value:form.model.secondary_addr"></td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>Blood Type</td>
                                <td>Birthdate</td>
                                <td></td>
                                <td>Civil Status</td>
                                <td></td>
                                <td>Phone No.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type="text" id="gender" data-bind="value:form.model.gender"></td>
                                <td><input type="text" id="blood_type" data-bind="value:form.model.blood_type"></td>
                                <td colspan=2><input type="text" id="birthdate" data-bind="value:form.model.birthdate"></td>
                                <td colspan=2><input type="text" id="civil_status" data-bind="value:form.model.civil_status"></td>
                                <td colspan=2><input type="text" id="contact_no" data-bind="value:form.model.contact_no"></td>
                            </tr>
                            <tr>
                                <td colspan=4>Emergency Contact Person</td>
                                <td colspan=2>Relationship</td>
                                <td colspan=2>Phone No.</td>
                            </tr>
                            <tr>
                                <td colspan=4><input type="text" id="emergency_person" data-bind="value:form.model.emergency_person"></td>
                                <td colspan=2><input type="text" id="emergency_relation" data-bind="value:form.model.emergency_relation"></td>
                                <td colspan=2><input type="text" id="emergency_phone" data-bind="value:form.model.emergency_phone"></td>
                            </tr>
                            <tr>
                                <td colspan=2>E-mail</td>
                                <td colspan=2></td>
                                <td colspan=2></td> 
                                <td colspan=2></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="email" data-bind="value:form.model.email"></td>
                                <td colspan=2></td>
                                <td colspan=2></td>
                                <td colspan=2></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card card-secondary mt-1">
                    <div class="card-header"> Government ID & Deductions </div>
                    <div class="card-body">
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td  colspan=2>SSS No</td>
                                <td>Deduct SSS</td>
                                <td  colspan=2>PHIC</td>
                                <td>Deduct PHIC</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="sss_no" data-bind="value:form.model.sss_no"></td>
                                <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.deduct_sss" id="deduct_sss"></div></div></td>
                                <td colspan=2><input type="text" id="phic_no" data-bind="value:form.model.phic_no"></td>
                                <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.deduct_phic" id="deduct_phic"></div></div></td>
                                <td></td>
                                <td></td>
                                
                            </tr>
                            <tr>
                                <td colspan="2">T.I.N.</td>
                                <td>Deduct WTax</td>
                                <td colspan="2">HDMF</td>
                                <td>HDMF Contri</td>
                                <td colspan="2">Manual WTax</td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="text" id="tin_no" data-bind="value:form.model.tin_no"></td>
                                <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.deduct_wtax" id="deduct_wtax"></div></div></td>
                                
                                <td colspan="2"><input type="text" id="hdmf_no" data-bind="value:form.model.hdmf_no"></td>
                                <td><input type="text" id="hdmf_contri" data-bind="value:form.model.hdmf_contri"></td>
                                <td><input type="text" id="manual_wtax" data-bind="value:form.model.manual_wtax"></td>
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
                                <td colspan=2>Date Regularized</td>
                               
                                <td colspan=2>Employee Type</td>
                                <td>Date Hired</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="employee_stat" data-bind="value:form.model.employee_stat"></td>
                                <td colspan=2><input type="text" id="date_regularized" data-bind="value:form.model.date_regularized"></td>
                                
                                
                                <td colspan=2><input type="text" id="pay_type" data-bind="value:form.model.pay_type"></td>
                                <td colspan=2><input type="text" id="date_hired" data-bind="value:form.model.date_hired"></td>
                            </tr>
                            <tr>
                                <td colspan=2>Exit Status</td>
                                <td colspan=2>Exit Date</td>
                                <td colspan=2>Level</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="exit_status" data-bind="value:form.model.exit_status"></td>
                                <td colspan=2><input type="text" id="exit_date" data-bind="value:form.model.exit_date"></td>
                                <td colspan=2><input type="text" id="emp_level" data-bind="value:form.model.emp_level"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=2>Mon - Fri</td>
                                <td colspan=2>Saturday</td>
                                <td colspan=2></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="sched_mtwtf" data-bind="value:form.model.sched_mtwtf"></td>
                                <td colspan=2><input type="text" id="sched_sat" data-bind="value:form.model.sched_sat"></td>
                                <td colspan=2></td>
                                <td></td>
                                <td></td>
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
                                <td>Fixed Rate</td>
                                <td colspan=2>Monthly Allowance</td>
                                <td colspan=2>Daily Allowance</td>
                            </tr>
                                @if($canSeeRates)
                                    <tr>
                                        <td colspan=2><input type="text" id="basic_salary" data-bind="value:form.model.basic_salary"></td>
                                        <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.is_daily" id="is_daily"></div></div></td>
                                        <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.fixed_rate" id="fixed_rate"></div></div></td>
                                        <td colspan=2><input type="text" id="monthly_allowance" data-bind="value:form.model.monthly_allowance"></td>
                                        <td colspan=2><input type="text" id="daily_allowance" data-bind="value:form.model.daily_allowance"></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan=2></td>
                                        <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.is_daily" id="is_daily"></div></div></td>
                                        <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.fixed_rate" id="fixed_rate"></div></div></td>
                                        <td colspan=2><input type="text" id="monthly_allowance" data-bind="value:form.model.monthly_allowance"></td>
                                        <td colspan=2><input type="text" id="daily_allowance" data-bind="value:form.model.daily_allowance"></td>
                                    </tr>
                                @endif
                            <tr>
                                <td colspan=2>Bank Acct </td>
                                <td>Retired</td>
                                <td colspan=2></td>
                                <td colspan=2></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="bank_acct" data-bind="value:form.model.bank_acct"></td>
                                <td><div class="form-group"><div class="form-check"><input class="form-check-input" type="checkbox" data-bind="checked:form.mirror.retired" id="retired"></div></div></td>
                                
                            </tr>
                        </table>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
@endsection

@include('app.employee-file.employee-master-data.js')
