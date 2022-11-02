@extends('layouts.app')

@section('contentheader_title')
  Compagnies
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Liste des compagnies</h3>
                </div>
                <div class="box-body">
                        @include('pim.companies._listing', ['companies' => $userCompanies])
                </div>
                <div class="box-footer clearfix">
                    <a href="{{ route('company.create') }}" class="btn btn-primary">
                        <i class="fa fa-fw fa-plus"></i>
                        <span class="hidden-sm hidden-xs">Créer une nouvelle compagnie</span>
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
        $('.table-companies').DataTable({
          "paging": true,
          "lengthChange": false,
          "columnDefs": [
            { "searchable": true, "targets": [0, 1] },
            { "orderable": false, "targets": [2] }
          ],
          "info": true,
          "autoWidth": false
        });
      });
    </script>
@endpush
