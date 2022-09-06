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

    
</style>
@section('title')
    <h4> One Time Deductions <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-secondary">
                        <div class="card-header">Types</div>
                        <div class="card-body"> 
                            <div id="typesgrid"></div>
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
                <div class="card card-secondary mt-1">
                   
                    <input type="hidden" id="id" data-bind="value:form.model.id" >
                    <div class="card-body">
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td colspan=2>Payroll Period</td>
                                <td colspan=2>Type</td>
                                <td colspan=2></td>
                               
                            </tr>
                            <tr>
                                <td colspan=2><input type="text" id="period_id" data-bind="value:form.model.period_id"></td>
                                <td colspan=2><input type="text" id="deduction_type" data-bind="value:form.model.deduction_type"></td>
                                <td colspan=2></td>
                            </tr>
                            <tr>
                                <td colspan=6>Remarks</td>
                              
                            </tr>
                            <tr>
                               <td colspan=6><input type="text" id="remarks" data-bind="value:form.model.remarks"></td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('app.deductions.one-time.js')