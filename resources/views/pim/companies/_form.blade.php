<div class="box-body">
    {!! form($form) !!}
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
