@section('confirm-attribute-edition')
    <div class="modal modal-danger fade" id="confirmAttributeEdition">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="{{ trans('confirms.no') }}" class="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('attribute.edit.confirmation.title') }}</h4>
                </div>

                <div class="modal-body"><p></p></div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline btn-no pull-left">{{ trans('confirms.no') }}</button>
                    <button type="button" class="btn btn-outline btn-yes">{{ trans('confirms.yes') }}</button>
                </div>
            </div>
        </div>
    </div>
@show

@push('scripts')
<script>
    $(function() {
        $('#confirmAttributeEdition').find('.btn-yes').click(function () {
            $('#form-edit-attribute').submit();
        });

        $('#confirmAttributeEdition').find('.btn-no').click(function () {
            $('#confirmDeletionForm').removeAttr('action');
        });

        $(document).on('click', '#btn-confirm-edit-attribute', function(e) {
            e.preventDefault();

            $('#confirmAttributeEdition').find('.modal-body p').html($(this).data('body'));

            $('#confirmAttributeEdition').modal();

            return false;
        });
    });
</script>
@endpush
