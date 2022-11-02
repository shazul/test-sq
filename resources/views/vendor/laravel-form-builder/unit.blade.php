@if($errors->has($nameKey . '.metric') || $errors->has($nameKey.'.imperial'))
    <?php $options['wrapper']['class'] .= ' has-error' ?>
@endif

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
    <?php $options['wrapper']['class'] .= ' unit-container' ?>
    <div class="{!! $options['wrapper']['class']!!}" >
    @endif
@endif

@if ($showLabel && $options['label'] !== false)
    {!! Form::label($name, $options['label'], $options['label_attr']) !!}
@endif

@if ($showField)
    <div class="input-group clearfix">
        <div class="input-container first">
            <input class="radio-preferred"@if ($options['values']['preferred'] == '') checked="checked" @elseif($options['values']['preferred'] == 'metric') checked="checked" @endif type="radio" name="{{ $name }}[preferred]" value="metric">
            <input class="form-control unit-format unit-metric" name="{{ $name }}[metric]" id="{{ $options['id'] }}-metric"
                   type="text" value="{{ old_value($name.'[metric]', $options['values']['metric']) }}">
            <span class="input-group-addon">{{ $options['specs']['metric_annotation'] }}</span>
        </div>

        <div class="input-container last pull-left">
            <input class="radio-preferred" type="radio" @if ($options['values']['preferred'] == 'imperial') checked="checked" @endif name="{{ $name }}[preferred]" value="imperial">
            <input class="form-control unit-format unit-imperial" name="{{ $name }}[imperial]" id="{{ $options['id'] }}-imperial"
                   type="text" value="{{ old_value($name.'[imperial]', $options['values']['imperial']) }}">
            <span class="input-group-addon">{{ $options['specs']['imperial_annotation'] }}</span>
        </div>
    </div>
    @include('vendor.laravel-form-builder.help_block')
@endif

@if (!Request::ajax())
@push('scripts')
@endif
<script type="text/javascript">
    $(function () {
        $(document).on('change', '#{{ $options['id'] }}-metric', function() {
            $(this).parent().parent().find('.unit-imperial').val($(this).val() {{ $options['operation'] }} {{ $options['specs']['imperial_measurement'] }});
        });
    });
</script>
@if (!Request::ajax())
@endpush
@endif

@if ($showError && isset($errors))
    @if ($errors->has($nameKey.'.metric'))
        @foreach ($errors->get($nameKey . '.metric') as $err)
            <div class="text-danger" {{ $options['errorAttrs'] }}>{{ $err }}</div>
        @endforeach
    @elseif ($errors->has($nameKey.'.imperial'))
        @foreach ($errors->get($nameKey . '.imperial') as $err)
            <div class="text-danger" {{ $options['errorAttrs'] }}>{{ $err }}</div>
        @endforeach
    @endif
@endif

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
    </div>
    @endif
@endif
