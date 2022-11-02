@extends('layouts.app')

@section('contentheader_title')
  {{ trans('importation.title') }}
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-body">
                {!! Form::open(['route' => ['importation.import', 'child-product'], 'files' => true]) !!}
                <div class="form-group">
                    {!! Form::label('imported-file', trans('importation.labelFile')) !!}
                    {!! Form::file('imported-file', ['class' => 'form-control', 'accept' => '.csv']) !!}
                </div>
                {!! Form::submit(trans('importation.button'), ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

