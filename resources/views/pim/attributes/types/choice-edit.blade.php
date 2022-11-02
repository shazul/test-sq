@extends('pim.attributes.types._layout')
@inject('field_file', \Pimeo\Forms\Fields\FieldFile)

@section('type-content')
    @foreach ($attribute->value->values[$languages->first()->code] as $i => $value)
        <div class="form-group">
            <label>
                {{ trans('attribute.edit.form.types.choice', ['number' => $i+1]) }}
            </label>

            @if (in_array(array_get($attribute->type->specs, 'sub_type'), ['image', 'imageNoDisplay']))
                @foreach ($languages as $n => $language)
                    <div class="input-group input-list">
                        <span class="input-group-addon">{{ $language->name }}</span>
                        <input type="text" name="choice[{{ $language->code }}][{{ $i }}][name]" class="form-control" value="{{ $attribute->value->values[$language->code][$i]['name'] or ''}}">
                    </div>
                    <input type="file" name="choice[{{ $language->code }}][{{ $i }}][image]" class="form-control-static">
                    @if (!empty($attribute->value->values[$language->code][$i]['image']))
                        <p class="form-control-static">
                            <img src="{{ env('FILES_ADDRESS') . '/' . $attribute->value->values[$language->code][$i]['image'] }}" width="150" height="150" />
                            <p class="form-control-static">
                                <strong>-- {{ $field_file->getFileRealName($attribute->value->values[$language->code][$i]['image']) }} --</strong>
                            </p>
                        </p>
                        <p class="form-control-static">
                            <input type="checkbox" name="choice[{{ $language->code }}][{{ $i }}][delete]" value="1" class="flat-blue">
                            {{ trans('attribute.edit.form.delete-image') }}
                        </p>
                    @endif
                @endforeach
            @else
                @foreach ($languages as $n => $language)
                    <div class="input-group input-list" v-bind:class="{'has-error': hasError($index, '{{ $language->code }}')}">
                        <span class="input-group-addon">{{ $language->name }}</span>
                        <input type="text" name="choice[{{ $language->code }}][{{ $i }}]" class="form-control" value="{{ $attribute->value->values[$language->code][$i] }}">
                    </div>
                @endforeach
            @endif
        </div>
    @endforeach
@endsection
