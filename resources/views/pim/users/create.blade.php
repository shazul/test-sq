@extends('layouts.app')

@section('contentheader_title')
    {{ trans('user.create.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                {!! Form::open(['route' => 'user.store', 'role' => 'form']) !!}

                @include('pim.users._form')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('htmlheader_extra')
    <link href="{{ asset('/plugins/iCheck/all.css') }}" rel="stylesheet" />
    <link href="{{ asset('/plugins/iCheck/square/blue.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
      $(function () {
        $('.select2').select2();

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
      });
    </script>
@endpush