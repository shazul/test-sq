@section('confirm-page-change')
    <div class="modal modal-warning fade" id="confirmPageChange" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="{{ trans('confirms.no') }}" class="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('confirms.page-change.title') }}</h4>
                </div>

                <div class="modal-body">{{ trans('confirms.page-change.body') }}</div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline btn-no pull-left">{{ trans('confirms.no') }}</button>
                    <a class="btn btn-danger btn-yes">{{ trans('confirms.yes') }}</a>
                </div>
            </div>
        </div>
    </div>
@show

@push('scripts')
<script>
    $('#confirmPageChange').on('show.bs.modal', function(e) {
        $(this).find('.btn-yes').attr('href', $(e.relatedTarget).data('href'));
    });
</script>
@endpush