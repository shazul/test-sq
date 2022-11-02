@extends('layouts.app')

@section('contentheader_title')
    {{ trans('parent-product.edit.title') }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="btn-group">
                    <a class="btn btn-primary btn-flat disabled">{{ trans('parent-product.edit.select-button') }}</a>
                    <a class="btn btn-primary btn-flat btn-change" href="#"
                        data-href="{{ route('parent-product.edit-nature', $parent_product->id) }}" data-toggle="modal"
                        data-target="#confirmPageChange"
                    >
                        {{ trans('parent-product.nature.select-button') }}
                    </a>
                </div>
                <div class="box-body" id="boxFormAttributes">
                    {!! form($form) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@include('pim.partials.add-attributes-inline', ['product_id' => $parent_product->id, 'product_route' => 'parent-product'])
@include('partials.confirms.page-change')
@endsection

@push('htmlheader_extra')
<link href="//cdnjs.cloudflare.com/ajax/libs/image-picker/0.2.4/image-picker.min.css" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/image-picker/0.2.4/image-picker.min.js"></script>
<script>
    $(function () {
        $(".select-2").select2(); // Don't use class select2

        $(".image-picker-label").imagepicker({
            show_label: true
        });

        $('#form_edit_parent_product').change(function(event) {
            validateMinRequired();
        });

        validateMinRequired();
    });

    function validateMinRequired()
    {
        var form = $('#form_edit_parent_product');
        $.ajax({
            url     : '{{ route('parent-product.validate-required-attributes') }}',
            type    : 'POST',
            data    : form.find(':not(input[name=_method])').serialize(),
            dataType: 'json',
        })
        .done(function(data) {
            $('#publish').prop('disabled', false);
        })
        .fail(function(data) {
            $('#publish').prop('disabled', true);
        });
    }
</script>
@endpush
