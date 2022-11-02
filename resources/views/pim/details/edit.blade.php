@extends('layouts.app')

@section('contentheader_title')
    {{ trans('detail.edit.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-flat disabled">{{ trans('detail.edit.select-button') }}</a>
                        <a class="btn btn-primary btn-flat btn-change" href="#" data-href="{{ route('detail.edit-attribute', $detail->id) }}" data-toggle="modal" data-target="#confirmPageChange">{{ trans('detail.edit-attribute.select-button') }}</a>
                    </div>
                    <div class="box-body" id="boxFormAttributes">
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('pim.partials.add-attributes-inline', ['product_id' => $detail->id, 'product_route' => 'detail'])
@include('partials.confirms.page-change')
@endsection
