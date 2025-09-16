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

    .k-file-validation-message .k-text-success {
        color : #ffae42 !important;
    }

    #dtrgrid {
        font-size:10pt;
    }

    .k-icon, .k-button-text {
        font-size : 9pt !important;
    }

    .k-window-title {
        font-size : 10pt !important;
    }

    button.fixwidth {
        width: 120px;
    }


</style>
@section('title')
    <h4> Payroll Register - Confi<h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
           
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header"> Unposted Payroll</div>
                        <div class="card-body"> 
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td colspan=4>Payroll Period</td>
                            </tr>
                            <tr>
                                <td colspan=2 > <input type="text" id="unposted_period"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=4>&nbsp;</td>
                            </tr>
                            <tr>
                                <td> <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : buttonHandler.compute"> Compute</button> </td>
                                <td> <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : buttonHandler.view"> View PDF</button> </td>
                                <td> <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : buttonHandler.download"> Download Excel</button> </td>
                                <td> <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : buttonHandler.post"> Post</button> </td>
                            </tr>
                            
                        </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header"> Posted Payroll </div>
                        <div class="card-body"> 
                        <table class="formTable" border=0 style="width:100%">
                            <tr>
                                <td colspan=4>Posted Payroll Period</td>
                            </tr>
                            <tr>
                                <td colspan=2 > <input style ="width:220px" type="text" id="posted_period"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan=4>&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;" > <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : buttonHandler.unpost"> Unpost</button> </td>
                               
                                <td style="text-align:center;" > <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : buttonHandler.downloadPosted"> Download Excel</button> </td>
                                <td style="text-align:center;" > <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : buttonHandler.downloadRCBC"> RCBC Template</button> </td>
                                <td style="text-align:center;" > <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : buttonHandler.financeTemplate"> Finance Temp</button> </td>
                                <!--  <td style="text-align:center;" >  <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base fixwidth" data-bind="click : buttonHandler.showOTBreakdown"> OT Breakdown</button> </td> -->
                             </tr>
                            
                        </table>
                        </div>
                    </div>
                </div>
                <div id="pop" style="display:none;">
                   <table style="width:100%">
                        <tr>
                            <td>
                                <div id="dtrgrid"></div>
                            </td>
                            <td style="width:170px;background-color: #f8f9fa;vertical-align:top;">
                                <div id="raw-logs"></div>
                            </td>
                        </tr>
                   </table>
                </div>
            </div>
        </div>
    </div>
@endsection </div>

@include('app.payroll-transaction.payroll-register-confi.js')