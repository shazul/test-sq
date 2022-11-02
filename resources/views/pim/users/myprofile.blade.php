@extends('layouts.app')

@section('contentheader_title')
    {{ trans('user.my_profile.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                {!! Form::model($user, ['route' => ['user.update-my-profile', $user], 'role' => 'form', 'method' => 'PUT']) !!}
                <div class="box-body">
                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                        <label for="first_name">{{ trans('user.my_profile.first_name') }}</label>
                        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('first_name'))
                            <span class="help-block">{{ $errors->first('first_name') }}</span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                        <label for="last_name">{{ trans('user.my_profile.last_name') }}</label>
                        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('last_name'))
                            <span class="help-block">{{ $errors->first('last_name') }}</span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">{{ trans('user.my_profile.email') }}</label>
                        {!! Form::text('email', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('email'))
                            <span class="help-block">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                        <label for="current_password">{{ trans('user.my_profile.current_password') }}</label>
                        {!! Form::password('current_password', ['class' => 'form-control']) !!}
                        @if ($errors->has('current_password'))
                            <span class="help-block">{{ $errors->first('current_password') }}</span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password">{{ trans('user.my_profile.password') }}</label>
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                        @if ($errors->has('password'))
                            <span class="help-block">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password_confirmation">{{ trans('user.my_profile.password_confirmation') }}</label>
                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-fw fa-save"></i>
                        {{ trans('user.my_profile.save') }}
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
