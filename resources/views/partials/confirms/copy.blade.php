<div class="modal modal-warning fade" id="confirmCopy" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="{{ trans('confirms.no') }}" class="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('confirms.copy-attributes.title') }}</h4>
            </div>

            <div class="modal-body">{{ trans('confirms.copy-attributes.body') }}</div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-outline btn-no pull-left">{{ trans('confirms.no') }}</button>
                <a class="btn btn-danger btn-yes">{{ trans('confirms.yes') }}</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $('#confirmCopy').on('show.bs.modal', function(e) {
        var form = $(e.relatedTarget).closest('form');
        $(this).find('.btn-yes').on('click', function(e) {
            form.submit();
            $(this).off('click');
        });
    });
</script>
@endpush
