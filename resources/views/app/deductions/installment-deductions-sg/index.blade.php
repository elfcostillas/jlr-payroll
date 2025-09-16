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
   
    .formTable {
        font-size: 10pt;
        color : white;
        table-layout: fixed;
        background-color: #6c757d; /*6c757d*/
    }

    .formTable tr td {
        padding : 4px 4px;
    }
    
    #toolbar,#toolbar2 {
        font-size:10pt !important;
        background-color:  #6c757d !important;
    }

    .k-pager-info .k-label {
        display : block !important;
        font-size : 9pt !important;
    }

    .k-button-text {
        font-size : 10pt !important;
    }

    
</style>
@section('title')
    <h4> Installments - Support Group <h4>
@endsection
@section('content')
    <div class="">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-secondary">
                        <div class="card-header">Employees</div>
                        <div class="card-body"> 
                            <div id="employeegrid"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card card-secondary">
                        <div class="card-header"> Deductions </div>
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
                   
                    <input type="hidden" id="id" data-bind="value:form.model.id" >
                    <div class="card-body">
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td colspan=2>Employee</td>
                                <td colspan=2>Start of Deduction</td>
                                <td colspan=2>Type</td>
                                
                               
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="biometric_id" data-bind="value:form.model.biometric_id"></td>
                                <td colspan=2><input type="text" id="period_id" data-bind="value:form.model.period_id"></td>
                                <td colspan=2><input type="text" id="deduction_type" data-bind="value:form.model.deduction_type"></td>
                                
                            </tr>
                            <tr>
                                <td colspan=1>Amount</td>
                                <td colspan=1>Terms</td>
                                <td colspan=1>Ammortizatoin</td>
                                <td colspan=1>Stop</td>
                                <td colspan=1></td>
                                {{-- <td colspan=1>Deduction Sched.</td> --}}
                            </tr>
                            <tr>
                                <td colspan=1><input type="text" id="total_amount" data-bind="value:form.model.total_amount"> </td>
                                <td colspan=1><input type="text" id="terms" data-bind="value:form.model.terms"> </td>
                                <td colspan=1><input type="text" id="ammortization" data-bind="value:form.model.ammortization" > </td>
                                <td colspan=1><input type="text" id="is_stopped" data-bind="value:form.model.is_stopped"> </td>
                                <td colspan=1></td>
                                {{-- <td colspan=1><input type="text" id="deduction_sched" data-bind="value:form.model.deduction_sched"></td> --}}
                            </tr>
                            <tr>
                                <td colspan=6>Remarks</td>
                            </tr>
                            <tr>
                               <!-- <td colspan=6><input type="text" id="remarks" data-bind="value:form.model.remarks"></td> -->
                                <td colspan=6>
                                    <textarea style="height:54px" name="remarks" id="remarks" rows="3" data-bind="value:form.model.remarks"></textarea>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('app.deductions.installment-deductions-sg.js')