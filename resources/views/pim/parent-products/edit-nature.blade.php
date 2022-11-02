@extends('layouts.app')

@section('contentheader_title')
    {{ trans('parent-product.nature.title') }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="btn-group">
                    <a class="btn btn-primary btn-flat btn-change" href="#"
                        data-href="{{ route('parent-product.edit', $product->id) }}" data-toggle="modal"
                        data-target="#confirmPageChange"
                    >
                        {{ trans('parent-product.edit.select-button') }}
                    </a>
                    <a class="btn btn-primary btn-flat disabled">
                        {{ trans('parent-product.nature.select-button') }}
                    </a>
                </div>
                <div class="box-body">
                    <div class="alert alert-warning">
                        <h4>
                            {{ trans('parent-product.nature.warning-title') }}
                        </h4>
                        {{ trans('parent-product.nature.warning-message') }}
                    </div>
                    {!! form($form) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.confirms.page-change')
@endsection
