@if($errors->has("$nameKey.image") || $errors->has("$nameKey.0.full_name"))
    @php($options['wrapper']['class'] .= ' has-error')
@endif

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div class="{!! $options['wrapper']['class'] !!}">
    @endif
@endif

@if ($showLabel && $options['label'] !== false)
    {!! Form::label($name . '[image]', $options['label'], $options['label_attr']) !!}
@endif

@if ($showField)
    @if(!empty($options['files'][$options['lang']]) && $options['files'][$options['lang']])
                    @foreach ($options['files'][$options['lang']] as $file)
                        <div class="saved-file">
                            @if (isset($file['full_name']))
                                {{-- If from POST data --}}
                                @if($options['type'] == 'image')
                                    <img src="{{ getenv('FILES_ADDRESS') }}/{{ $file['full_name'] }}" width="150">
                                @else
                                    {!! Html::image('img/generic.png', 'file', array('width'=> 80)) !!}
                                @endif
                                <input type="hidden" name="{{ $name }}[][full_name]" value="{{ $file['full_name'] }}"/>
                                <span class="remove-file"
                                      id="remove-file-{{ $options['id'] }}-{{ $options['lang'] }}"><i
                                        class="fa fa-trash"></i></span>
                                <p class="filename">
                                    - {{$options['file_to_image_name']( getenv('FILES_ADDRESS') . '/' . $file['full_name'])}}
                                    -</p>
                            @elseif (isset($file['file_path']))
                                {{-- If already saved (In database) --}}
                                <input type="hidden" name="{{ $name }}[][full_name]" value="{{ $file['file_path'] }}"/>
                                @if($options['type'] == 'image')
                                    <img src="{{ getenv('FILES_ADDRESS') }}/{{ $file['file_path'] }}" width="150">
                                @else
                                    {!! Html::image('img/generic.png', 'file', array('width'=> 80)) !!}
                                @endif
                                <span data-img-name="{{ $file['file_path'] }}" class="delete-file"
                                      id="delete-file-{{ $options['id'] }}-{{ $options['lang'] }}">
                        <i class="fa fa-trash"></i>
                    </span>
                                <p class="filename">- {{ $file['name'] }} -</p>
                @endif
                        </div>
                    @endforeach
    @endif

                <div style="display:none"
                     id="dropzone-{{ $options['id'] }}-{{ $options['lang'] }}"
                     data-lang="{{ $options['lang'] }}"
                     data-id="{{ $options['id'] }}"
                     class="dropzone inline-control">
        @if(!isset($file['full_name']) && !isset($file['file_path']))
            <input class="empty-file" type="hidden" name="{{ $name }}[][empty_file]" value="" />
        @endif
    </div>

    @include('vendor.laravel-form-builder.help_block')
@endif

@if (!Request::ajax())
                @push('scripts')
@endif

<script>
    $(function () {
        Dropzone.autoDiscover = false;
        var pimDropzone = new Dropzone("#dropzone-{{ $options['id'] }}-{{ $options['lang'] }}", {
            url: "{{ route('parent-product.update.image') }}",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            addRemoveLinks: true,
            dictDefaultMessage: '{{ trans('dropzone.dictDefaultMessage') }}',
            @foreach ($options['dropzone_options'] as $key => $value)
            {{ $key }}: '{{ $value }}',
            @endforeach
        });

        var max_files = pimDropzone.options.maxFiles;

        pimDropzone.on("success", function(file, response) {
            $('.dropzone').first().append('<input type="hidden" name="{{ $name }}[][full_name]" value="'+response['full_name']+'" />');
            $(this.element).children('.empty-file').remove();
            $('form').trigger('change'); //Pour validations bouton published sur le form.onchange
        });
        pimDropzone.on("maxfilesexceeded", function(file) {
            this.removeAllFiles();
            this.addFile(file);
        });
        pimDropzone.on("removedfile", function(file){
            $('.dropzone').children('input[name="{{$name . '[][full_name]'}}"]').last().remove();
            if(this.files.length == false){
                $(this.element).append('<input class="empty-file" type="hidden" name="{{ $name }}[][empty_file]" value="" />');
            }
            $('form').trigger('change'); //Pour validations bouton published sur le form.onchange
        });
        $(document).on('click', '#delete-file-{{ $options['id'] }}-{{ $options['lang'] }}', function() {
            var imgName = $(this).attr('data-img-name');
            var dropzone = $(this).parent().next('.dropzone');
            var empty_input_name  = 'attributes['+ dropzone.data('id') + ']['+dropzone.data('lang')+']';
            var input_html = '<input class="empty-file" type="hidden" name="'+empty_input_name+'[][empty_file]" value="" />';

            if (dropzone.is(':hidden') && max_files == 1){
                dropzone.show();
                dropzone.append(input_html)
            }

            if(max_files == undefined && $(this).parent().parent().children('.saved-file').length == 1){
                dropzone.append(input_html)
            }

            $('.dropzone').first().append('<input type="hidden" name="attributes[{{ $options['id'] }}][filesToDelete][]" value="' + imgName + '" />');
            $(this).parent().remove();
            $('form').trigger('change'); //Pour validations bouton published sur le form.onchange
        });
        $(document).on('click', '#remove-file-{{ $options['id'] }}-{{ $options['lang'] }}', function() {
            var dropzone = $(this).parent().next('.dropzone');
            var empty_input_name  = 'attributes['+ dropzone.data('id') + ']['+dropzone.data('lang')+']';
            var input_html = '<input class="empty-file" type="hidden" name="'+empty_input_name+'[][empty_file]" value="" />';

            // Single file - After deletion of an uploaded image, show the hidden dropzone & add empty-file hidden input
            if (dropzone.is(':hidden') && max_files == 1) {
                dropzone.show();
                dropzone.append(input_html);
            }

            // Multiple file - After deletion of all uploaded image, append an empty-file hidden input
            if (max_files == undefined && $(this).parent().parent().children('.saved-file').length == 1) {
                dropzone.append(input_html);
            }

            $(this).parent().remove();
            $('form').trigger('change'); //Pour validations bouton published sur le form.onchange
        });

        // Show the dropzone if no files uploaded and max_files is 1
        if ($(pimDropzone.element).prev('div.saved-file').children('input').length == 0 && max_files == 1) {
            $(pimDropzone.element).show();
        }

        // If this is a multiple file upload, then always show dropzones
        if (max_files == undefined) {
            $(pimDropzone.element).show();
        }
    });
</script>

@if (!Request::ajax())
                @endpush
@endif

@if ($showError && isset($errors))
    @foreach ($errors->get($nameKey . '.image') as $err)
                    <div class="text-danger" {!! $options['errorAttrs'] !!}>{{ $err }}</div>
    @endforeach
                @foreach ($errors->get("$nameKey.0.full_name") as $err)
                    <div class="text-danger" {!! $options['errorAttrs'] !!}>{{ $err }}</div>
                @endforeach
@endif

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        </div>
    @endif
@endif
