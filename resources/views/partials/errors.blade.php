@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
        <h4>
            <i class="icon fa fa-ban"></i>
            {{ trans('errors.form.title') }}
        </h4>
        {{ trans_choice('errors.form.message', $errors->count()) }}
        @if (config('app.debug') && !App::environment('testing'))
            {{ dump($errors) }}
        @endif
    </div>
@endif
