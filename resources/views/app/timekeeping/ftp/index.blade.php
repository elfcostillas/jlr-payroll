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
    <h4> Failure to Punch <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-default">
                        <div class="card-header"> </div>
                        <div class="card-body"> 
                            <div id="maingrid"></div>
                        </div>
                    </div>
                </div>
              
            </div>
        </div>
    </div>
@endsection

@include('app.timekeeping.ftp.js')