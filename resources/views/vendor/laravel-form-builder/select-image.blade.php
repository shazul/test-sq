@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
    <div {!! $options['wrapperAttrs'] !!} >
    @endif
@endif

@if ($showLabel && $options['label'] !== false)
    {!! Form::label($name, $options['label'], $options['label_attr']) !!}
@endif

@if ($showField)
    <select name="{{ $name }}" {!! Html::attributes($options['attr']) !!}>
    @foreach ($options['choices'] as $value => $optionsAttrs)
        @if (isset($options['selected']) && in_array($value, $options['selected']))
        <option data-img-src="{{ $optionsAttrs['image-src'] }}" value="{{ $value }}" selected>
        @else
        <option data-img-src="{{ $optionsAttrs['image-src'] }}" value="{{ $value }}">
        @endif
            {{ $optionsAttrs['label']}}
        </option>
    @endforeach
    </select>
    @include('vendor.laravel-form-builder.help_block')
@endif

@include('vendor.laravel-form-builder.errors')

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
    </div>
    @endif
@endif
