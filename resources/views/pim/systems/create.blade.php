@extends('layouts.app')

@section('contentheader_title')
    {{ trans('system.create.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    {!! form($form) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
