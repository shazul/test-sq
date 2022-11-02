@extends('layouts.auth')

@section('htmlheader_title')
    {{ trans('errors.503.title') }}
@endsection

@section('contentheader_title')
    {{ trans('errors.503.page') }}
@endsection

@section('content')
    <div class="error-page">
        <h2 class="headline text-red">503</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i>{{ trans('errors.503.oops') }}</h3>
            <p>
                {{ trans('errors.503.description') }}
                {{ trans('errors.503.meanwhile') }}
                <a href='{{ url('/home') }}'>
                {{ trans('errors.503.return') }}</a>.
            </p>
        </div>
    </div><!-- /.error-page -->
@endsection
