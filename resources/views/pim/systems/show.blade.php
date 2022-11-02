@extends('layouts.app')

@section('contentheader_title')
    {{ trans('system.show.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="panel panel-default flat pad form-readonly">
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
            @include('pim.systems._layers-list')
        </div>
    </div>
@endsection

@include('partials.form-readonly')
