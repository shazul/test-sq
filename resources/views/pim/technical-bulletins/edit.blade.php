@extends('layouts.app')

@section('contentheader_title')
    {{ trans('technical-bulletin.edit.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('technical-bulletin.edit.description') }}</h3>
                </div>
                <div class="box-body">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-flat disabled">{{ trans('technical-bulletin.edit.select-button') }}</a>
                    </div>
                    <div class="panel panel-default flat pad" id="boxFormAttributes">
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('pim.partials.add-attributes-inline', ['product_id' => $technical_bulletin->id, 'product_route' => 'technical-bulletin'])
@include('partials.confirms.page-change')
@endsection

@push('htmlheader_extra')
<link href="{{ asset('/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
<link href="//cdnjs.cloudflare.com/ajax/libs/image-picker/0.2.4/image-picker.min.css" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/image-picker/0.2.4/image-picker.min.js"></script>
<script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
<script>
    $(function () {
        $(".select-2").select2(); // Don't use class select2

        $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
        });

        $(".image-picker-label").imagepicker({
            show_label: true
        });
    });
</script>
@endpush
