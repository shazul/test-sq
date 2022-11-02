<div class="box box-primary">
    <div class="box-header">
          <h3 class="box-title">Ajout d'attributs</h3>
     </div><!-- /.box-header -->
    <div class="box-body">
        <div class="form-group">
            @include('pim.partials.add-attributes-inline-selectAttributes')
        </div>
        <div class="form-group">
            <button type="submit" disabled="disabled" class="btn btn-primary" id="btnAddAttribute">Ajouter</button>
        </div>
</div>
</div>
@push('scripts')
<script>
$(function () {

    $(document).on('change', '#addAttributes', function(event){
        if ($(this).val() !== "") {
            $('#btnAddAttribute').prop( "disabled", false );
        }else{
            $('#btnAddAttribute').prop( "disabled", true );
        }
    });

    $(document).on('click', '#btnAddAttribute', function(event) {
        event.preventDefault();

        $.ajax({
            url: '{{ route("$product_route.add-inline-attribute", $product_id) }}',
            type: 'POST',
            data: {
                attributes: $('#addAttributes').val(),
                _token: $('input[name="_token"').val()
            },
            beforeSend: function() {
                $('#boxFormAttributes form .btn-primary, #btnAddAttribute').prop('disabled', true);
            }
        })
        .done(function(data) {
            $newAttribute = $(data.form);
            $('#boxFormAttributes form .form-group:last').after($newAttribute.html());
            $('#addAttributes').replaceWith(data.selectAttributes);
        })
        .always(function() {
            $('#boxFormAttributes form .btn-primary, #btnAddAttribute').prop('disabled', false);
            $('#addAttributes').val('');
            $('#btnAddAttribute').prop( "disabled", true );
        });
    });

    $(document).on('click', '.inline-delete', function(event) {
        that = this;
        attribute_id = $(this).attr('id');
        attributeFieldsSelector = [
            '[id^=attributes_' + attribute_id + ']',
            '[id^=dropzone-' + attribute_id + ']',
            '[id^=' + attribute_id + '-metric]',
        ].join(',');
        $formGroupToRemove = $(attributeFieldsSelector).closest('.form-group');
        $.ajax({
            url: '{{ route("$product_route.delete-inline-attribute", $product_id) }}',
            type: 'POST',
            data: {
                attribute_id: attribute_id,
                _token: $('input[name="_token"').val()
            },
            beforeSend: function() {
                $('#boxFormAttributes form .btn-primary, #btnAddAttribute').prop('disabled', true);
                $formGroupToRemove.addClass('animated-fast fadeOut');
            }
        })
        .done(function(data) {
            $formGroupToRemove.remove();
            $('#addAttributes').replaceWith(data.selectAttributes);
        })
        .error(function(data) {
            $formGroupToRemove.removeClass('fadeOut');
        })
        .always(function() {
            $('#boxFormAttributes form .btn-primary, #btnAddAttribute').prop('disabled', false);
            $('#addAttributes').val('');
            $('#btnAddAttribute').prop( "disabled", true );
        });
    });
});
</script>
@endpush
