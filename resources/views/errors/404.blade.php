@extends('layouts.auth')

@section('htmlheader_title')
    {{ trans('errors.404.title') }}
@endsection

@section('contentheader_title')
    {{ trans('errors.404.page') }}
@endsection

@section('content')
<div class="error-page">
    <h2 class="headline text-yellow"> 404</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i>{{ trans('errors.404.oops') }}</h3>
        <p>
            {{ trans('errors.404.description') }}
            {{ trans('errors.404.meanwhile') }}
            <a href='{{ url('/home') }}'>
            {{ trans('errors.404.return') }}</a>.
        </p>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
@endsection
