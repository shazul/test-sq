@extends('layouts.app')

@section('contentheader_title')
    {{ trans('system.edit-layer.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('system.edit-layer.description') }}</h3>
                </div>
                <div class="box-body">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-flat btn-change" href="#" data-href="{{ route('system.edit', [$system->id]) }}" data-toggle="modal" data-target="#confirmPageChange">{{ trans('system.edit.select-button') }}</a>
                        <a class="btn btn-primary btn-flat disabled" >{{ trans('system.edit-layer.select-button') }}</a>
                    </div>

                    @if (null !== $form->getFormOption('url'))
                    <div class="panel panel-default flat pad">
                        {!! form($form) !!}
                    </div>
                    @endif
                </div>
            </div>
            @include('pim.systems._layers-list', ['add' => true, 'current_language' => $current_language])
        </div>
    </div>
    @include('partials.confirms.page-change')
@endsection
