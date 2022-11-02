@extends('layouts.app')

@section('contentheader_title')
    {{ trans('menu.home') }}
@endsection

@section('content')
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">{{ trans('views.home.welcome') }}</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="{{ trans('views.tool.collapse') }}"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="{{ trans('views.tool.remove') }}"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">

      </div><!-- /.box-body -->
    </div><!-- /.box -->
@endsection
