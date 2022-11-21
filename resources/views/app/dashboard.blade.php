@extends('layouts.theme.layout')

@section('title')
    <h4> Dashboard <h4>
@endsection
@section('content')
    <div class="container">
        <div id="viewModel" >
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-default">
                        <div class="card-header"><h5> Employee </h5> </div>
                        <div class="card-body"> 
                           <h6> {{ $count['total'] }} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-default">
                        <div class="card-header"><h5> Regular </h5></div>
                        <div class="card-body"> 
                            <h6> {{ $count['reg'] }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-default">
                        <div class="card-header"><h5> Probationary  </h5></div>
                        <div class="card-body"> 
                            <h6> {{ $count['prob'] }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-default">
                        <div class="card-header"><h5> Support Group </h5></div>
                        <div class="card-body"> 
                           <h6> {{ $count['support'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jquery')

@endsection