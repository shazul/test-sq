@extends('layouts.auth')

@section('htmlheader_title')
    {{ trans('auth.login') }}
@endsection

@push('htmlheader_extra')
    <link href="{{ asset('/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('body_class')
    login-page
@endpush

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/home') }}"><img alt="Soprema PIM" src="/img/logo.png"></a>
        </div><!-- /.login-logo -->

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>{{ trans('passwords.whoops') }}</strong> {{ trans('passwords.input_error') }}<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('auth.signin_info') }}</p>
            <form action="{{ url('/login') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name="email"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="{{ trans('auth.password_placeholder') }}" name="password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember"> {{ trans('auth.remember') }}
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('auth.signin') }}</button>
                    </div><!-- /.col -->
                </div>
            </form>

            <a href="{{ url('/password/reset') }}">{{ trans('auth.forget_password') }}</a><br>

        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->
@endsection

@push('scripts')
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endpush
