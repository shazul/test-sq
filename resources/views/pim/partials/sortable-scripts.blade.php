@push('scripts')
<script>
    $(function() {
        $('[data-sort="{{ $sort_column or '' }}"]').removeClass().addClass('sorting_{{ $sort_order or '' }}').data('order', '{{ $sort_order }}');

        var queryString = {};

        $.each(document.location.search.substr(1).split('&'), function(i, query) {
            var keyValue = query.split('=');
            if (keyValue[1] != undefined) {
                queryString[keyValue[0]] = keyValue[1];
            }
        });

        $('.sorting, .sorting_asc, .sorting_desc').on('click', function() {
            var order = $(this).data('order') == 'asc' ? 'desc' : 'asc';
            var key = $(this).data('sort') + ':' + order;

            queryString['sort'] = key;

            var url = document.location.pathname + '?';
            var urlParts = [];

            for (var i in queryString) {
                urlParts.push(i + '=' + queryString[i]);
            }

            window.location.href = url + urlParts.join('&');
        });
    });
</script>
@endpush
