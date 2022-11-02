@if ($showField)
    @foreach($options['value'] as $lang_code => $form_data)
        <div class="form-group data-group @if($errors->get($options['dotted_name'].".$lang_code.0")) has-error @endif">
            {!! Form::label($name, $options['label'] . ' (' . language_code_trans($lang_code) . ')', $options['label_attr']) !!}
            <div class="input-group data-group-container"  data-name="{{ $name . '['.$lang_code.']'}}" >
                @forelse($form_data as $key => $form_values)
                    <?php $formFieldName = $name . '['.$lang_code.']' . '['. $key .']' ?>
                    <div class="data-container-{{$key}} data-container">
                        {!! Form::input($type, $formFieldName, $form_values, $options['attr']) !!}
                        <span class="delete-data" data-index="{{ $key }}"><i class="fa fa-trash"></i></span>
                    </div>
                    <?php $nameKey =  $options['dotted_name'].".$lang_code.$key" ?>
                    @include('vendor.laravel-form-builder.errors')
                @empty
                    <div class="data-container-0 data-container">
                        {!!  Form::input($type, $name . '['.$lang_code.']' . '[0]', "", $options['attr']) !!}
                        <span class="delete-data" data-index="0"><i class="fa fa-trash"></i></span>
                    </div>
                @endforelse

                @include('vendor.laravel-form-builder.help_block')
                <button class="btn btn-primary btn-add-data" type="button">
                    {{ trans('attribute.add') }}
                </button>
            </div>
        </div>
    @endforeach
@endif

@if (!Request::ajax())
@push('scripts')
@endif
    <script type="text/javascript">

        $(function(){
            var limit = 3;
            $(document).ready(handleAddButton);
            $('.btn-add-data').on('click', addInput);
            $(document).on('click', '.delete-data', handleRemoval);

            function handleRemoval()
            {
                var index = $(this).data('index');
                var left = $(this).parent().parent().children('.data-container').length;
                $input_container =  $('.data-container-'+index);

                if(left == 1){
                    $input_container.children('input').val('');
                }
                else{
                    $input_container.remove();
                    reindexData();
                    handleAddButton();
                }
            }

            function reindexData(){
                $('.data-group-container').each(function(){
                    $incrementor = 0;
                    $base_name = $(this).data('name');

                    $(this).children('[class^="data-container"]').each(function(){
                        $(this).attr('class', 'data-container-' + $incrementor );
                        $(this).addClass('data-container');
                        $(this).children('input').attr('name', $base_name + '[' +$incrementor + ']');
                        $(this).children('.delete-data').attr('data-index', $incrementor);

                        $incrementor++;
                    });
                });
            }

            function addInput(){
                $index = $('.data-group-container').first().find('.data-container').length;

                if($index < limit){
                    $('.data-group-container').each(function(){
                        var new_name = $(this).data('name') + '['+ $index +']';
                        var html = '<div class="data-container data-container-' + $index + '">';
                        html += '<input class="form-control data" name="'+ new_name +'" type="text" value=""/>';
                        html += '<span class="delete-data" data-index="'+ $index +'"><i class="fa fa-trash"></i>';
                        html += '</span></div>';

                        $last = $(this).children('[class^="data-container-"]').last();

                        if($last.length){
                            $last.after(html);
                        }
                        else {
                            $(this).children('.btn-add-data').before(html);
                        }
                    })
                }

                handleAddButton();
                reindexData();
            }

            function handleAddButton(){
                if($('.data-container').length / 2 < limit){
                    $('.btn-add-data').show();
                }
                else{
                    $('.btn-add-data').hide();
                }
            }
        });
    </script>
@if (!Request::ajax())
@endpush
@endif
