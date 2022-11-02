@extends('layouts.app')

@section('contentheader_title')
    {{ trans('child-product.create.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    {!! form($form) !!}
                </div>
            </div>
        </div>
    </div>
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
        var form = $('#form_create_child_product');
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
