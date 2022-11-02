@extends('layouts.app')

@section('contentheader_title')
    {{ trans('attribute.index.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('attribute.models.'.$model) }}</h3>

                    <div class="box-tools">
                        <input type="text" id="attributes-query" placeholder="{{ trans('attribute.index.table.search') }}" class="form-control pull-right input-sm">
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-with-buttons" id="attributes-table">
                        <thead>
                            <tr>
                                <th>{{ trans('attribute.index.table.header.name') }}</th>
                                <th>{{ trans('attribute.index.table.header.type') }}</th>
                                @if($model == 'system')
                                    <th>{{ trans('building-component.index.table.header') }}</th>
                                @else
                                    <th>{{ trans('attribute.index.table.header.natures') }}</th>
                                @endif
                                <th>{{ trans('attribute.index.required') }}</th>
                                <th class="text-right">{{ trans('attribute.index.table.header.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $attribute)
                                <tr>
                                    <td class="text-nowrap text-left">{!! $attribute->label->present()->value !!}</td>
                                    <td class="text-nowrap text-left">{{ trans('attribute_types.'.$attribute->type->code) }}</td>
                                    <td class="text-nowrap text-left">
                                        @foreach ($attribute->natures as $nature)
                                            <abbr class="label label-{{ $nature->code }}"
                                                  title="{{ trans('attribute.natures.'.$nature->code) }}">{{ trans('attribute.natures-short.'.$nature->code) }}</abbr>
                                        @endforeach
                                        @foreach ($attribute->buildingComponents as $component)
                                            <abbr class="label label-{{ $component->code }}"
                                                  title="{{ trans('building-component.component.'.$component->code) }}">{{ $component->abbr }}</abbr>
                                        @endforeach
                                    </td>
                                    <td>@if ($attribute->is_min_requirement) O @endif</td>
                                    <td class="text-right table-btn nowrap">
                                        <div class="btn-group">
                                            @can('edit', $attribute)
                                                <a href="{{ route('attribute.edit', $attribute) }}" class="btn btn-flat btn-warning">
                                                    <i class="fa fa-fw fa-edit"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('attribute.index.table.edit') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('attribute.show', $attribute) }}" class="btn btn-flat btn-info">
                                                    <i class="fa fa-fw fa-eye"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('attribute.index.table.show') }}</span>
                                                </a>
                                            @endcan

                                            @can('delete', $attribute)
                                                <a href="{{ route('attribute.destroy', $attribute) }}" class="btn btn-flat btn-danger btn-delete" data-body="{{ trans('attribute.confirm.body', ['name' => $attribute->name]) }}">
                                                    <i class="fa fa-fw fa-trash"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('attribute.index.table.delete') }}</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="box-footer clearfix">
                    @can('create-attribute')
                        <a href="{{ route('attribute.create', $model) }}" class="btn btn-primary">
                            <i class="fa fa-fw fa-plus"></i>
                            <span class="hidden-sm hidden-xs">{{ trans('attribute.index.create') }}</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    @include('partials.confirms.deletion')
@endsection

@push('scripts')
<script>
    $(function() {
        var table = $('#attributes-table').DataTable({
            searching: true,
            ordering: true,
            columns: [
                null,
                null,
                { orderable: false },
                null,
                { orderable: false, searchable: false }
            ],
            language: {
                paginate: {
                    previous: '&laquo;',
                    next: '&raquo;'
                }
            },
            dom: '<"row"<"col-sm-12"tp>>'
        });

        $('.box-footer').append($('#attributes-table_paginate').addClass('pull-right pagination-sm no-margin'));

        $('#attributes-query').keyup(function() {
            table.search(this.value).draw();
        });
    });
</script>
@endpush
