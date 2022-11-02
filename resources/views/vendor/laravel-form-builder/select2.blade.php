@if ($showLabel && $showField)
    <?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
@endif

@if ($showLabel && $options['label'] !== false)
    <?= Form::label($name, $options['label'], $options['label_attr']) ?>
@endif

@if ($showField)
    <input type='hidden' name="<?php echo substr($name, 0, strpos($name, '[]')); ?>" value="novalue">
    <?= Form::select($name, (array)$options['choices'], $options['selected'], $options['attr']) ?>
    @include('vendor.laravel-form-builder.help_block')
@endif

@include('vendor.laravel-form-builder.errors')

@if ($showLabel && $showField)
    <?php if ($options['wrapper'] !== false): ?>
    </div>
    <?php endif; ?>
@endif

@if (!Request::ajax())
@push('scripts')
@endif
    <script>
        $(document).ready(function() {
            $(".multi-select2").select2({
                maximumSelectionLength: 3
            });
        });
    </script>
@if (!Request::ajax())
@endpush
@endif
