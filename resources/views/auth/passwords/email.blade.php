@extends('layouts.auth')

@section('htmlheader_title')
    {{ trans('passwords.recovery.title') }}
@endsection

@push('body_class')
    login-page
@endpush

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/home') }}"><img alt="Soprema PIM" src="/img/logo.png"></a>
        </div><!-- /.login-logo -->

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>{{ trans('passwords.whoops') }}</strong> {{ trans('passwords.input_error')}}<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('passwords.recovery.reset') }}</p>
            <form action="{{ url('/password/email') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                <div class="row">
                    <div class="col-xs-2">
                    </div><!-- /.col -->
                    <div class="col-xs-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('passwords.recovery.send') }}</button>
                    </div><!-- /.col -->
                    <div class="col-xs-2">
                    </div><!-- /.col -->
                </div>
            </form>

            <a href="{{ url('/login') }}">{{ trans('passwords.recovery.login') }}</a><br>

        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->
@endsection

@push('scripts')
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
