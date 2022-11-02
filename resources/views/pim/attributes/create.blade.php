@extends('layouts.app')

@section('contentheader_title')
    {{ trans('attribute.create.title') }}
@endsection

@section('contentheader_description')
    {{ trans('attribute.models.'.$model) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['route' => 'attribute.store', 'role' => 'form']) !!}
                <div id="vue">
                    {!! Form::hidden('company_id', $company->id) !!}
                    {!! Form::hidden('model_type', $model) !!}

                    <div class="box box-default">
                        <div class="box-body">
                            @include('partials.errors')

                            <div class="form-group
                            @foreach ($languages as $language)
                                @if ($errors->has('label.'.$language->code))
                                    has-error
                                    @break
                                @endif
                            @endforeach
                                ">
                                <label>{{ trans('attribute.create.form.label') }}</label>

                                @foreach ($languages as $language)
                                    <div class="input-group input-list{{ $errors->has('label.'.$language->code) ? ' has-error' : '' }}">
                                        <span class="input-group-addon">{{ $language->name }}</span>
                                        <input type="text" name="label[{{ $language->code }}]" value="{{ old('label.'.$language->code) }}" class="form-control">
                                    </div>
                                @endforeach

                                @foreach ($languages as $language)
                                    @if ($errors->has('label.'.$language->code))
                                        <span class="help-block">{{ $errors->first('label.'.$language->code) }}</span>
                                        @break
                                    @endif
                                @endforeach
                            </div>

                            @if (in_array($model, ['parent_product', 'child_product']))
                                {!! Form::hidden('has_nature', 1) !!}
                                <div class="form-group{{ $errors->has('natures') ? ' has-error' : '' }}">
                                    <label for="natures">{{ trans('attribute.create.form.natures') }}</label>
                                    <div class="form-group">
                                        @foreach ($natures as $i => $nature)
                                            <label class="checkbox-inline">
                                                {!! Form::checkbox('natures[]', $nature->id, null, ['class' => 'flat-blue']) !!}
                                                {{ $nature->name }}
                                            </label>
                                        @endforeach
                                        @if ($errors->has('natures'))
                                            <span class="help-block">{{ $errors->first('natures') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if (in_array($model, ['system']))
                                {!! Form::hidden('has_building_component', 1) !!}
                                <div class="form-group{{ $errors->has('buildingComponents') ? ' has-error' : '' }}">
                                    <label for="buildingComponents">{{ trans('attribute.create.form.building_components') }}</label>
                                    <div class="form-group">
                                        @foreach ($buildingComponents as $i => $component)
                                            <label class="checkbox-inline">
                                                {!! Form::checkbox('buildingComponents[]', $component->id, null, ['class' => 'flat-blue']) !!}
                                                {{ trans('building-component.component.'.$component->code) }}
                                            </label>
                                        @endforeach
                                        @if ($errors->has('buildingComponents'))
                                            <span class="help-block">{{ $errors->first('buildingComponents') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="box box-default">
                        <div class="box-body">
                            <div class="form-group{{ $errors->has('attribute_type_id') ? ' has-error' : '' }}">
                                <label for="type">{{ trans('attribute.create.form.type') }}</label>
                                <select name="attribute_type_id" class="form-control" id="type" v-select="type" :options="types">
                                    <option value="">{{ trans('attribute.create.form.select-type') }}</option>
                                </select>
                                @if ($errors->has('attribute_type_id'))
                                    <span class="help-block">{{ $errors->first('attribute_type_id') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div v-if="isType('choice')">
                        @include('pim.attributes.types.choice')
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-fw fa-floppy-o"></i>
                    {{ trans('attribute.create.save') }}
                </button>

                <a href="{{ route('attribute.index', $model) }}" class="btn btn-warning">
                    <i class="fa fa-fw fa-reply"></i>
                    {{ trans('attribute.create.cancel') }}
                </a>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript" src="/js/vue.js"></script>
<script>
    $(function() {
        $("select.select2").select2();

        $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
            checkboxClass: 'icheckbox_minimal-blue'
        });

        Vue.directive('select', {
            twoWay: true,
            priority: 1000,
            params: ['options'],
            bind: function() {
                var self = this;
                $(this.el).select2({data: this.params.options}).on('change', function() {
                    self.set(this.value);
                });
            },
            update: function(value) {
                $(this.el).val(value).trigger('change');
            },
            unbind: function() {
                $(this.el).off().select2('destroy');
            }
        });

        Vue.directive('checkbox', {
            bind: function() {
                $(this.el).iCheck({
                    checkboxClass: 'icheckbox_minimal-blue'
                });
            },
            unbind: function() {
                $(this.el).off().iCheck('destroy');
            }
        });

        window.vm = new Vue({
            el: '#vue',
            data: {
                languages: {!! $languages !!},
                types: {!! $typesSelect !!},
                type: '{{ old('attribute_type_id') }}',
                choices: {!! json_encode($values) !!},
                errors: {!! json_encode($errors->toArray()) !!},
                currentMaxKey: {{ $currentMaxKey }},
            },
            computed: {
                choicesCount: function () {
                    var count = 1;

                    for (var i in this.languages) {
                        if (this.choices[this.languages[i].code]) {
                            count = Math.max(count, this.choices[this.languages[i].code].length);
                        }
                    }

                    return count;
                },
                error: function () {

                    for (var i in this.errors) {
                        if (i.substr(0, 6) == 'choice') {
                            return this.errors[i];
                        }
                    }

                    return false;
                }
            },
            methods: {
                addChoice: function() {
                    this.currentMaxKey += 1;
                    @foreach ($languages as $language)
                        this.choices.{{ $language->code }}.push('');
                    @endforeach
                },
                removeChoice: function(index, id) {
                    @foreach ($languages as $language)
                        this.choices.{{ $language->code }}.splice(index, 1);
                    @endforeach
                    this.attributesValuesToDelete.push(id);
                    this.showWarningEdit = 1;
                },
                hasError: function(index, code) {
                    if (!code.push) {
                        var key = ['choice', code, index].join('.');
                        return this.errors[key];
                    }

                    for (var i in code) {
                        var key = ['choice', code[i], index].join('.');
                        if (this.errors[key]) {
                            return true;
                        }
                    }

                    return false;
                },
                hasErrors: function() {
                    return this.errors != false;
                },
                isType: function(type) {
                    for (var i in this.types) {
                        if (this.types[i].id == this.type && this.types[i].type == type) {
                            return true;
                        }
                    }

                    return false;
                }
            }
        });
    });
</script>
@endpush
