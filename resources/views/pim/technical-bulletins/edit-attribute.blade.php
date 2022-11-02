@extends('layouts.app')

@section('contentheader_title')
    {{ trans('technical-bulletin.edit-attribute.title') }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('technical-bulletin.edit-attribute.description') }}</h3>
            </div>
            <div class="box-body" id="vue">
                <div class="btn-group">
                    <a class="btn btn-primary btn-flat btn-change" href="#"
                        data-href="{{ route('technical-bulletin.edit', $technical_bulletin->id) }}"
                        data-toggle="modal" data-target="#confirmPageChange">
                        {{ trans('technical-bulletin.edit.select-button') }}
                    </a>
                    <a class="btn btn-primary btn-flat disabled">{{ trans('technical-bulletin.edit-attribute.select-button') }}</a>
                </div>
                <form method="POST" action="{{ route('technical-bulletin.update-attribute', $technical_bulletin->id) }}" @submit="saveEnabled=false">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                @include('pim.partials.edit-attribute-form', ['product' => $technical_bulletin])
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.confirms.page-change')
@endsection
