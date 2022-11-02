@extends('layouts.app')

@section('contentheader_title')
    {{ trans('system.edit.title') }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('system.edit.description') }}</h3>
            </div>
            <div class="box-body">
                <div class="btn-group">
                    <a class="btn btn-primary btn-flat disabled">{{ trans('system.edit.select-button') }}</a>
                    <a class="btn btn-primary btn-flat btn-change" href="#"
                        data-href="{{ route('system.add-layer-groups', [$system->id]) }}"
                        data-toggle="modal" data-target="#confirmPageChange"
                    >
                        {{ trans('system.edit-layer.select-button') }}
                    </a>
                </div>
                <div class="panel panel-default flat pad" id="boxFormAttributes">
                    {!! form($form) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@include('pim.partials.add-attributes-inline', ['product_id' => $system->id, 'product_route' => 'system'])
@include('pim.systems._layers-list')
@include('partials.confirms.page-change')
@endsection
