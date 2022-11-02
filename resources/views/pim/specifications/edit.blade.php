@extends('layouts.app')

@section('contentheader_title')
    {{ trans('specification.edit.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-flat disabled">{{ trans('specification.edit.select-button') }}</a>
                    </div>
                    <div class="box-body" id="boxFormAttributes">
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('pim.partials.add-attributes-inline', ['product_id' => $specification->id, 'product_route' => 'specification'])
@include('partials.confirms.page-change')
@endsection
