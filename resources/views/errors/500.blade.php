@extends('layouts.auth')

@section('htmlheader_title')
    {{ trans('errors.500.title') }}
@endsection

@section('contentheader_title')
    {{ trans('errors.500.page') }}
@endsection

@section('content')
    <div class="error-page">
        <h2 class="headline text-red">500</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i>{{ trans('errors.500.oops') }}</h3>
            <p>
                {{ trans('errors.500.description') }}
                {{ trans('errors.500.meanwhile') }}
                <a href='{{ url('/home') }}'>
                {{ trans('errors.500.return') }}</a>.
            </p>
        </div>
    </div><!-- /.error-page -->
@endsection
