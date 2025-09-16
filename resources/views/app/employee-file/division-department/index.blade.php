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

    
</style>
@section('title')
    <h4> Divisions and Departments <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-default">
                        <div class="card-header"> <h5>Divisions</h5> </div>
                        <div class="card-body"> 
                            <div id="maingrid"></div>   
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-default">
                        <div class="card-header"> <h5>Departments</h5> </div>
                        <div class="card-body"> 
                            <div id="subgrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('app.employee-file.division-department.js')