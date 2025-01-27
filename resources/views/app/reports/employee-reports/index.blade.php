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
    <h4> Reports<h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Employee Records </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td colspan=2>Location</td>
                                    <td colspan=2>Division</td>
                                    <td colspan=2>Department</td>
                                    <td colspan=2></td>
                                </tr>
                                <tr>
                                    <td colspan=2><input type="text" id="location_id" ></td>
                                    <td colspan=2><input type="text" id="division_id" ></td>
                                    <td colspan=2><input type="text" id="dept_id" ></td>
                                    <td colspan=2></td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download'><i class="fas fa-download"></i> Download Employee List</button></td>
                                    <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download_weekly'><i class="fas fa-download"></i> Download Support Group List</button></td>
                                    <td colspan="4"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Quick Reports </div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td colspan=2>Location</td>
                                    <td colspan=2>Division</td>
                                    <td colspan=2>Department</td>
                                    <td colspan=2></td>
                                </tr>
                                <tr>
                                    <td colspan=2><input type="text" id="location_id_qr" ></td>
                                    <td colspan=2><input type="text" id="division_id_qr" ></td>
                                    <td colspan=2><input type="text" id="dept_id_qr" ></td>
                                    <td colspan=2></td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2"><button type="button" class="btn btn-block btn-danger btn-sm" data-bind='click:buttonHandler.download_qr'><i class="fas fa-download"></i> Print </button></td>
                                    <!-- <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download_weekly_qr'><i class="fas fa-download"></i> Download Weekly</button></td> -->
                                    <td colspan="4"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Employee List </div>
                        <div class="card-body"> 
                            <div style="height : 12rem;" >
                                @foreach($headers as $header)
                                    <span style="float:left;margin:4px;width :200px;display:block;"> <input type="checkbox" class="include_header" data-bind="checked:included" style="margin-right : 8px;" value="{{ $header->id }}" name="" id=""> {{ $header->header_label }}  </span>
                                @endforeach
                            </div>
                            <table class="formTable" border=0 style="width:100%">
                              
                                    <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download_custom'><i class="fas fa-download"></i> JLR Download Excel</button></td>
                                    <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download_custom_sg'><i class="fas fa-download"></i> Support Group Download Excel</button></td>
                                    <td colspan="4"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Employee Records - Weekly</div>
                        <div class="card-body"> 
                            <table class="formTable" border=0 style="width:100%">
                                <tr>
                                    <td colspan=2>Location</td>
                                    <td colspan=2>Division</td>
                                    <td colspan=2>Department</td>
                                    <td colspan=2></td>
                                </tr>
                                <tr>
                                    <td colspan=2><input type="text" id="location_id_w" ></td>
                                    <td colspan=2><input type="text" id="division_id_w"></td>
                                    <td colspan=2><input type="text" id="dept_id_w" ></td>
                                    <td colspan=2></td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download'><i class="fas fa-download"></i> Download Excel</button></td>
                                    <td colspan="2"><button type="button" class="btn btn-block btn-success btn-sm" data-bind='click:buttonHandler.download_weekly'><i class="fas fa-download"></i> Download Weekly</button></td>
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
@endsection

@include('app.reports.employee-reports.js')