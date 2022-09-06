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
    <h4> Deductions <h4>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-default">
                    {{-- <div class="card-header"> <h5>Semi Monthly</h5> </div> --}}
                    <div class="card-header"></div>
                    <div class="card-body"> 
                        <div id="viewModel" >
                            <div id="maingrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('app.settings.deduction-type.js')