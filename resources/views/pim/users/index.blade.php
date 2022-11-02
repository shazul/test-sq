@extends('layouts.app')

@section('contentheader_title')
  {{ trans('user.index.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('user.index.description') }}</h3>
                </div>
                <div class="box-body">
                        @include('pim.users._listing', ['users' => $users])
                </div>
                <div class="box-footer clearfix">
                    <a href="{{ route('user.create') }}" class="btn btn-primary">
                        <i class="fa fa-fw fa-plus"></i>
                        <span class="hidden-sm hidden-xs">{{ trans('user.index.create') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('partials.confirms.deletion')
@endsection

@push('scripts')
    <script>
      $(function () {
        $('.table-users').DataTable({
          "paging": true,
          "lengthChange": false,
          "columnDefs": [
            { "searchable": true, "targets": [0, 1] },
            { "orderable": false, "targets": [2, 3, 4] }
          ],
          "info": true,
          "autoWidth": false
        });
      });
    </script>
@endpush
