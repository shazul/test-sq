@extends('layouts.app')

@section('contentheader_title')
    {{ trans('child-product.edit.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('child-product.edit.description') }}</h3>
                </div>
                <div class="box-body">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-flat disabled">{{ trans('child-product.edit.select-button') }}</a>
                        <a class="btn btn-primary btn-flat btn-change" href="#"
                            data-href="{{ route('child-product.edit-parent', $child_product->id) }}"
                            data-toggle="modal" data-target="#confirmPageChange"
                        >
                            {{ trans('child-product.edit-parent.select-button') }}
                        </a>
                    </div>
                    <div class="box-body" id="boxFormAttributes">
                        @include('partials.errors')

                        @if ($child_product->status != 'fresh' && $child_product->parentProduct)
                            @include('pim.child-products.copy')
                        @endif
                        {!! form($form, ['no-errors-bag' => true]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('pim.partials.add-attributes-inline', ['product_id' => $child_product->id, 'product_route' => 'child-product'])
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

        $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
        });

        $(".image-picker-label").imagepicker({
            show_label: true
        });

        $('#form_edit_child_product').change(function(event) {
            validateMinRequired()
        });

        validateMinRequired();
    });

    function validateMinRequired()
    {
        var form = $('#form_edit_child_product');
        $.ajax({
            url     : '{{ route('child-product.validate-required-attributes') }}',
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
