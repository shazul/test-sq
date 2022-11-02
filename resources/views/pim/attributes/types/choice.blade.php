@extends('pim.attributes.types._layout')

@section('type-content')
    <div class="form-group" track-by="$index" v-for="(choiceIndex, choice) in choices.{{ $languages->first()->code }}">
        <label>
            {{ trans('attribute.create.form.types.choice') }}
        </label>

        @if(!isset($attribute->options['not_deletable']))
            <button type="button" class="btn btn-xs btn-danger pull-right" v-on:click="removeChoice(choiceIndex, choice.key)">
                <i class="fa fa-fw fa-remove"></i>
                {{ trans('attribute.create.form.types.remove') }}
            </button>
        @endif

        <div class="input-group input-list" v-for="language in languages" v-bind:class="{'has-error': hasError(choiceIndex, language.code)}">
            <span class="input-group-addon">@{{ language.name }}</span>
            <input type="text" name="choice[@{{ language.code }}][@{{ choiceIndex }}]" class="form-control" value="@{{ choices[language.code][choiceIndex] }}">
        </div>
    </div>

    <div v-cloak v-show="hasErrors() && error != false" class="form-group has-error">
        <span class="help-block">@{{ error }}</span>
    </div>

    @if(!isset($attribute->options['not_deletable']))
        <div class="form-group">
            <button type="button" class="btn btn-info" v-on:click="addChoice()">
                <i class="fa fa-fw fa-plus"></i>
                {{ trans('attribute.create.form.types.add') }}
            </button>
        </div>
    @endif
@endsection
