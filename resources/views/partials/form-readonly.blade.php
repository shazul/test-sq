@push('scripts')
<script>
    $(function() {
        $('.form-readonly').find('input, select, textarea').attr('disabled', 'disabled');
        $('.form-readonly').find('.dropzone, .delete-file, .delete-data').remove();
    });
</script>
@endpush
