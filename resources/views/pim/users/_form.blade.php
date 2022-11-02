<div class="box-body">
    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
        <label for="first_name">{{ trans('user.form.first_name') }}</label>
        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('first_name'))
            <span class="help-block">{{ $errors->first('first_name') }}</span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
        <label for="last_name">{{ trans('user.form.last_name') }}</label>
        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('last_name'))
            <span class="help-block">{{ $errors->first('last_name') }}</span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email">{{ trans('user.form.email') }}</label>
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
        @if ($errors->has('email'))
            <span class="help-block">{{ $errors->first('email') }}</span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password">{{ trans('user.form.password') }}</label>
        {!! Form::password('password', ['class' => 'form-control']) !!}
        @if ($errors->has('password'))
            <span class="help-block">{{ $errors->first('password') }}</span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label for="password_confirmation">{{ trans('user.form.password_confirmation') }}</label>
        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
        @if ($errors->has('password_confirmation'))
            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('groups') ? ' has-error' : '' }}">
        <label for="groups">{{ trans('user.form.groups') }}</label>
        {!! Form::select('groups[]',
            $groups->pluck('name', 'id'),
            isset($user) ? $user->groups->lists('id')->toArray() : null,
            ['multiple' => 'multiple', 'class' => 'form-control select2']) !!}
        @if ($errors->has('groups'))
            <span class="help-block">{{ $errors->first('groups') }}</span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('companies') ? ' has-error' : '' }}">
        <label for="groups">{{ trans('user.form.companies') }}</label>
        {!! Form::select('companies[]',
            $companies->pluck('name', 'id'),
            isset($user) ? $user->companies->lists('id')->toArray() : null,
            ['multiple' => 'multiple', 'class' => 'form-control select2']) !!}
        @if ($errors->has('companies'))
            <span class="help-block">{{ $errors->first('companies') }}</span>
        @endif
    </div>
    <div class="form-group">
        {!! Form::checkbox('active', null, null, ['class' => 'minimal']) !!}
        <label for="active">{{ trans('user.form.active') }}</label>
    </div>
</div>
<div class="box-footer">
    <button type="submit" class="btn btn-primary">
        <i class="fa fa-fw fa-save"></i>
        {{ trans('user.form.save') }}
    </button>

    <a href="{{ route('user.index') }}" class="btn btn-warning">
        <i class="fa fa-fw fa-reply"></i>
        {{ trans('user.form.cancel') }}
    </a>
</div>
