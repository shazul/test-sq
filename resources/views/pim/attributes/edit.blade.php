@extends('layouts.app')

@section('contentheader_title')
    {{ $attribute->label->present()->value }}
@endsection

@section('contentheader_description')
    {{ trans('attribute.edit.description') }}
@endsection

@section('content')
    <div class="row" id="vue">
        <div class="col-md-12">
            {!!
                Form::model($attribute,
                [
                    'route'    => ['attribute.update', $attribute],
                    'role'     => 'form',
                    'method'   => 'PUT',
                    'enctype'  => 'multipart/form-data',
                    'id'       => 'form-edit-attribute'
                ])
            !!}
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
                            <label>{{ trans('attribute.edit.form.label') }}</label>

                            @foreach ($languages as $language)
                                <div class="input-group input-list{{ $errors->has('label.'.$language->code) ? ' has-error' : '' }}">
                                    <span class="input-group-addon">{{ $language->name }}</span>
                                    <input type="text" name="label[{{ $language->code }}]" value="{{ old('label.'.$language->code, $attribute->label->getValue($language->code)) }}" class="form-control">
                                </div>
                            @endforeach

                            @foreach ($languages as $language)
                                @if ($errors->has('label.'.$language->code))
                                    <span class="help-block">{{ $errors->first('label.'.$language->code) }}</span>
                                    @break
                                @endif
                            @endforeach
                        </div>

                        @if (in_array($attribute->model_type, ['parent_product', 'child_product']))
                            @if ($attribute->type->public && !$attribute->is_min_requirement)
                                {!! Form::hidden('has_nature', 1) !!}
                            @endif
                            <div class="form-group{{ $errors->has('natures') ? ' has-error' : '' }}">
                                <label for="natures">{{ trans('attribute.edit.form.natures') }}</label>
                                <div class="form-group">
                                    @foreach ($natures as $nature)
                                        <label class="checkbox-inline">
                                            @if ($attribute->type->public && !$attribute->is_min_requirement)
                                                <input type="checkbox" name="natures[]" value="{{ $nature->id }}" class="flat-blue2"
                                                    @change="showWarningEdit = 1"
                                                @if ($errors->any())
                                                    @if (!empty(old('natures')) && in_array($nature->id, old('natures')))
                                                        checked="checked"
                                                    @endif
                                                @elseif ($attribute->natures->contains($nature->id))
                                                    checked="checked"
                                                @endif
                                                />
                                            @else
                                                {!!
                                                    Form::checkbox(
                                                        'natures[]',
                                                        $nature->id,
                                                        $attribute->natures->contains($nature->id),
                                                        ['class' => 'flat-blue',
                                                        'disabled' => true]
                                                    )
                                                !!}
                                            @endif
                                            {{ $nature->name }}
                                        </label>
                                    @endforeach
                                    @if ($errors->has('natures'))
                                        <span class="help-block">{{ $errors->first('natures') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if (in_array($attribute->model_type, ['system']))
                            @if ($attribute->type->public && !$attribute->is_min_requirement)
                                {!! Form::hidden('has_building_component', 1) !!}
                            @endif
                            <div class="form-group{{ $errors->has('buildingComponents') ? ' has-error' : '' }}">
                                <label for="building-components">{{ trans('attribute.edit.form.building_components') }}</label>
                                <div class="form-group">
                                    @foreach($buildingComponents as $component)
                                        <label class="checkbox-inline">
                                            @if ($attribute->type->public && !$attribute->is_min_requirement)
                                                <input type="checkbox" name="buildingComponents[]"
                                                       value="{{ $component->id }}"
                                                       class="flat-blue2"
                                                @change="showWarningEdit = 1"
                                                @if ($errors->any())
                                                    @if (!empty(old('buildingComponents')) && in_array($component->id, old('buildingComponents')))
                                                        checked="checked"
                                                    @endif
                                                @elseif ($attribute->buildingComponents->contains($component->id))
                                                    checked="checked"
                                                @endif
                                                />
                                            @else
                                                {!!
                                                    Form::checkbox(
                                                        'buildingComponents[]',
                                                        $component->id,
                                                        $attribute->buildingComponents->contains($component->id),
                                                        ['class' => 'flat-blue',
                                                        'disabled' => true]
                                                    )
                                                !!}
                                            @endif
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
                            <label for="type">{{ trans('attribute.edit.form.type') }}</label>
                            @if ($attribute->type->public && !$attribute->is_min_requirement)
                                <select name="attribute_type_id" class="form-control" id="type" v-select="type" :options="types" @change="showWarningEdit = 1">
                                    <option value="">{{ trans('attribute.edit.form.select-type') }}</option>
                                </select>
                                @if ($errors->has('attribute_type_id'))
                                    <span class="help-block">{{ $errors->first('attribute_type_id') }}</span>
                                @endif
                            @else
                                <input type="text" readonly="readonly" value="{{ trans('attribute_types.'.$attribute->type->code) }}" class="form-control">
                                {{ Form::hidden('attribute_type_id', $attribute->type->code) }}
                            @endif
                        </div>
                    </div>
                </div>

                @if (!$attribute->type->public)
                    @if ($attribute->type->specs['type'] == 'choice')
                        @include('pim.attributes.types.choice-edit')
                    @endif
                @else
                    <div v-if="isType('choice')">
                        @include('pim.attributes.types.choice')
                    </div>
                @endif

                @if(!isset($attribute->options['not_editable']))
                <input v-if="showWarningEdit && editWillAffect" type="hidden" name="deleteLinkAttributes" value="1" />
                <button v-if="showWarningEdit && editWillAffect" type="submit" class="btn btn-danger btn-confirm"
                    id="btn-confirm-edit-attribute"
                    data-body="{{ trans_choice('attribute.edit.confirmation.body', $willAffect, ['nombre' => $willAffect]) }}">
                    <i class="fa fa-fw fa-floppy-o"></i>
                    {{ trans('attribute.edit.save') }}
                </button>
                <button v-else type="submit" class="btn btn-primary btn-confirm">
                    <i class="fa fa-fw fa-floppy-o"></i>
                    {{ trans('attribute.edit.save') }}
                </button>
                @endif

                <a href="{{ route('attribute.index', $attribute->model_type) }}" class="btn btn-warning">
                    <i class="fa fa-fw fa-reply"></i>
                    {{ trans('attribute.edit.cancel') }}
                </a>

                @can('delete', $attribute)
                    {!! Html::delete('attribute.destroy', $attribute, 'attribute.edit.delete', [], 'attribute.confirm.body', ['name' => $attribute->label->present()->value]) !!}
                @endcan
                <input type="hidden" value="@{{ attributesValuesToDelete.join(',') }}" name="attributesValuesToDelete" />
            {!! Form::close() !!}
        </div>
    </div>

    @can('delete', $attribute)
        @include('partials.confirms.deletion')
    @endcan
    @include('pim.attributes._confirm-edition')
@endsection

@push('scripts')
<script type="text/javascript" src="/js/vue.js"></script>
<script>
    $(function() {
        $("select.select2").select2();

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
                type: '{{ old('attribute_type_id', $attribute->type->code) }}',
                choices: {!! json_encode($values) !!},
                errors: {!! json_encode($errors->toArray()) !!},
                showWarningEdit: 0,
                editWillAffect: {{ $willAffect }},
                currentMaxKey: {{ $currentMaxKey }},
                attributesValuesToDelete: []
            },
            computed: {
                choicesCount: function() {
                    var count = 1;

                    for (var i in this.languages) {
                        if (this.choices[this.languages[i].code]) {
                            count = Math.max(count, this.choices[this.languages[i].code].length);
                        }
                    }

                    return count;
                },
                error: function() {

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
                    var key = ['choice', code, index].join('.');
                    return this.errors[key];
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

        $(document).on('change', '#type', function() {
            window.vm.showWarningEdit = 1;
        });
    });
</script>
@endpush
