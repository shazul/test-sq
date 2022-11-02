@extends('layouts.app')

@section('contentheader_title')
    {{ trans('system.layer.edit.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('system.layer.edit.description') }}</h3>
                </div>
                <div class="box-body">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-flat btn-change" href="#" data-href="{{ route('system.edit', [$system->id]) }}" data-toggle="modal" data-target="#confirmPageChange">{{ trans('system.edit.select-button') }}</a>
                        <a class="btn btn-primary btn-flat btn-change" href="#" data-href="{{ route('system.add-layer-groups', [$system->id]) }}" data-toggle="modal" data-target="#confirmPageChange">{{ trans('system.edit-layer.select-button') }}</a>
                    </div>

                    @if (null !== $form->getFormOption('url'))
                    <div class="panel panel-default flat pad">
                        {!! form_start($form) !!}
                        {!! form_row($form->type_layer) !!}
                        <div id="substrat" style="display:none">
                        @foreach(get_current_company_languages() as $language)
                            @php( $nom = 'nom_'. $language->code)
                            {!! form_row($form->$nom) !!}
                        @endforeach
                        @foreach(get_current_company_languages() as $language)
                            @php( $fonction = 'fonction_' . $language->code)
                            {!! form_row($form->$fonction) !!}
                        @endforeach
                        </div>
                        {!! form_rest($form) !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('partials.confirms.page-change')
@endsection

@push('scripts')
<script>
    $(function () {
        toggleAffichageTypeLayer();

        $(document).on('click', '#type_layer_parent, #type_layer_substrat', function() {
            toggleAffichageTypeLayer();
        });
    });

    function toggleAffichageTypeLayer()
    {
        isParent = $('#type_layer_parent')[0].checked;

        $('#parent_product').parent().toggle(isParent);
        $('#substrat').toggle(!isParent);
    }

    $(".select-2").select2();
</script>
@endpush
