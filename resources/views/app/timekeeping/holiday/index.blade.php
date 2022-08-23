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
    <h4> Holiday <h4>
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
                                <div id="pop" style="display:none">
                                    <div class="card card-default">
                                        {{-- <div class="card-header"> <h5>User Rights</h5> </div> --}}
                                        <div class="card-body"> 
                                             <ul class="list-group mb-1">
                                                        <li class="list-group-item active"> Locations </li>
                                            @foreach($locations as $l)
                                                <li class="list-group-item list-group-item-dark"> <input type="checkbox" class="urights" data-bind="checked:location" value="{{ $l->id }}"> &nbsp;&nbsp;&nbsp; {{ $l->location_name }} </li>
                                                  
                                            @endforeach
    
    
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
              
            </div>
        </div>
    </div>
@endsection

@include('app.timekeeping.holiday.js')