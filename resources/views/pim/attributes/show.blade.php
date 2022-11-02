@extends('layouts.app')

@section('contentheader_title')
    {{ trans('attribute.show.title') }}
@endsection

@section('contentheader_description')
    {{ trans('attribute.show.description') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label>{{ trans('attribute.show.form.label') }}</label>

                        @foreach ($languages as $language)
                            <div class="input-group input-list">
                                <span class="input-group-addon">{{ $language->name }}</span>
                                {!! Form::text('', $attribute->label->getValue($language->code), ['class' => 'form-control', 'readonly' => true]) !!}
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label>{{ trans('attribute.show.form.name') }}</label>
                        {!! Form::text('', $attribute->name, ['class' => 'form-control', 'readonly' => true]) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('attribute.show.form.type') }}</label>
                        {!! Form::text('', $attribute->type->name, ['class' => 'form-control', 'readonly' => true]) !!}
                    </div>

                    @if(isset($buildingComponents) && $attribute->model_type == 'system')
                        <div class="form-group">
                            <label>{{ trans('attribute.show.form.building_components') }}</label>
                            @foreach ($buildingComponents as $component)
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('buildingComponents[]', $component->id, ($attribute->buildingComponents->contains($component->id)), ['class' => 'flat-blue', 'disabled' => true]) !!}
                                        {{ trans('building-component.component.'.$component->code) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-group">
                        <label>{{ trans('attribute.show.form.company') }}</label>
                        {!! Form::text('', $attribute->company->name, ['class' => 'form-control', 'readonly' => true]) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('attribute.show.form.natures') }}</label>
                        @foreach ($natures as $nature)
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('natures[]', $nature->id, ($attribute->natures->contains($nature->id)), ['class' => 'flat-blue', 'disabled' => true]) !!}
                                    {{ $nature->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label>{{ trans('attribute.show.form.model-type') }}</label>
                        {!! Form::text('', trans('attribute.models.'.$attribute->model_type), ['class' => 'form-control', 'readonly' => true]) !!}
                    </div>

                    @if ($attribute->system_type)
                        <div class="form-group">
                            <label>{{ trans('attribute.show.form.system-type') }}</label>
                            {!! Form::text('', trans('attribute.systems'), ['class' => 'form-control', 'readonly' => true]) !!}
                        </div>
                    @endif

                    <div class="form-group">
                        <label>{{ trans('attribute.show.form.has-value') }}</label>
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('has_value', 1, $attribute->has_value, ['class' => 'flat-blue', 'id' => 'has_value', 'disabled' => true]) !!}
                                {{ trans('attribute.show.form.has-value-checkbox') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('attribute.show.form.is-parent-attribute') }}</label>
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('is_parent_attribute', 1, $attribute->is_parent_attribute, ['class' => 'flat-blue', 'id' => 'is_parent_attribute', 'disabled' => true]) !!}
                                {{ trans('attribute.show.form.is-parent-attribute-checkbox') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route('attribute.index', $attribute->model_type) }}" class="btn btn-info">
                        <i class="fa fa-fw fa-reply"></i>
                        {{ trans('attribute.show.back') }}
                    </a>

                    @can('edit', $attribute)
                        <a href="{{ route('attribute.edit', $attribute) }}" class="btn btn-warning">
                            <i class="fa fa-fw fa-pencil"></i>
                            {{ trans('attribute.show.edit') }}
                        </a>
                    @endcan

                    @can('delete', $attribute)
                        {!! Html::delete('attribute.destroy', $attribute, 'attribute.show.delete', [], 'attribute.confirm.body', ['name' => $attribute->name]) !!}
                    @endcan
                </div>
            </div>
        </div>
    </div>

    @can('delete', $attribute)
        @include('partials.confirms.deletion')
    @endcan
@endsection

@push('htmlheader_extra')
<link href="{{ asset('/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
<script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
<script>
    $(function () {
        $(".select2").select2();

        $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
        });
    });
</script>
@endpush
