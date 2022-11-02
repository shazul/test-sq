@extends('layouts.app')

@section('contentheader_title')
    {{ trans('company.create.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    {{ trans('company.create.language-notice')}}
                </div>
                {!! form_start($form) !!}
                <div class="box-body">
                    {!! form_until($form, 'languages') !!}
                </div>
                <div class="box-footer">
                    {!! form_rest($form) !!}
                </div>
                {!! form_end($form) !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
      $(function () {
        $('.select2').select2();
      });
    </script>
@endpush
