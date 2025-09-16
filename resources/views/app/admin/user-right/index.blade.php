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
    <h4> User Rights <h4>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header"> <h5>Users</h5> </div>
                    <div class="card-body"> 
                        <div id="viewModel" >
                            <div id="maingrid"></div>

                            <div id="pop" style="display:none">
                                <div class="card card-default">
                                    {{-- <div class="card-header"> <h5>User Rights</h5> </div> --}}
                                    <div class="card-body"> 
                                        @foreach($rights as $main)
                                            <ul class="list-group mb-1">
                                                    <li class="list-group-item active"> {{ $main->menu_desc }} </li>
                                            
                                                @foreach($main->sub as $module)
                                                    <li class="list-group-item list-group-item-dark"> <input type="checkbox" class="urights" data-bind="checked:rights" value="{{ $module->id }}"> &nbsp;&nbsp;&nbsp; {{ $module->sub_menu_desc }} </li>
                                                @endforeach
                                            </ul>
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

@include('app.admin.user-right.js')