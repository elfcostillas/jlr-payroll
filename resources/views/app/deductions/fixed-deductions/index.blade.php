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
    <h4> Fixed Deductions <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header"> Deductions </div>
                        <div class="card-body">
                            <div id="maingrid"></div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
@endsection

@include('app.deductions.fixed-deductions.js')