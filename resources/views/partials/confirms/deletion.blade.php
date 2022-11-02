@section('confirm-deletion')
    {!! Form::open(['method' => 'DELETE', 'id' => 'confirmDeletionForm']) !!}
    {!! Form::close() !!}

    <div class="modal modal-danger fade" id="confirmDeletion">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="{{ trans('confirms.no') }}" class="close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('confirms.deletion.title') }}</h4>
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
        $('#confirmDeletion').find('.btn-yes').click(function () {
            $('#confirmDeletionForm').submit();
        });

        $('#confirmDeletion').find('.btn-no').click(function () {
            $('#confirmDeletionForm').removeAttr('action');
        });

        $('.btn-delete').click(function (e) {
            e.preventDefault();

            $('#confirmDeletionForm').attr('action', $(this).attr('href'));
            $('#confirmDeletion').find('.modal-body p').html($(this).data('body'));

            $('#confirmDeletion').modal();

            return false;
        });
    });
</script>
@endpush
