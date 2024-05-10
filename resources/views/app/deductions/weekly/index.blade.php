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
   

    
</style>
@section('title')
    <h4> Other Income - Weekly <h4>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header"> <h5>Payroll Period</h5> </div>
                    <div class="card-body"> 
                        <div id="viewModel" >
                            <div id="maingrid"></div>
                        </div>
                        <div id="pop" style="display:none;background-color:#212529;">
                            <div class="card-body">
                                <input type="text" id ="period_id" readonly>
                            </div>

                            <div id = "compgrid" >

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('app.deductions.weekly.js')