@extends('layouts.app')

@section('contentheader_title')
    {{ trans('attribute.index.title') }}
@endsection

@section('contentheader_description')
    {{ trans('attribute.index.description.'.$model) }}
@endsection

@section('content')
    @foreach ($systems as $system => $attributes)
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary box-datatable">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('attribute.systems.'.($system ?: 'default')) }}</h3>

                        <div class="box-tools">
                            <input type="text" placeholder="{{ trans('attribute.index.table.search') }}"
                                   data-target="#attributes-table-{{ $system }}"
                                   class="form-control pull-right input-sm data-search">
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-with-buttons dataTable"
                               id="attributes-table-{{ $system }}">
                            <thead>
                            <tr>
                                <th>{{ trans('attribute.index.table.header.name') }}</th>
                                <th>{{ trans('attribute.index.table.header.type') }}</th>
                                <th class="text-right">{{ trans('attribute.index.table.header.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($attributes as $attribute)
                                <tr>
                                    <td class="text-nowrap text-left">{!! $attribute->label->present()->value !!}</td>
                                    <td class="text-nowrap text-left">{{ trans('attribute_types.'.$attribute->type->code) }}</td>
                                    <td class="text-right table-btn nowrap">
                                        <div class="btn-group">
                                            @can('edit', $attribute)
                                            <a href="{{ route('attribute.edit', $attribute) }}"
                                               class="btn btn-flat btn-warning">
                                                <i class="fa fa-fw fa-edit"></i>
                                                <span
                                                    class="hidden-sm hidden-xs">{{ trans('attribute.index.table.edit') }}</span>
                                            </a>
                                            @else
                                                <a href="{{ route('attribute.show', $attribute) }}"
                                                   class="btn btn-flat btn-info">
                                                    <i class="fa fa-fw fa-eye"></i>
                                                    <span
                                                        class="hidden-sm hidden-xs">{{ trans('attribute.index.table.show') }}</span>
                                                </a>
                                                @endcan

                                                @can('delete', $attribute)
                                                <a href="{{ route('attribute.destroy', $attribute) }}"
                                                   class="btn btn-flat btn-danger btn-delete"
                                                   data-body="{{ trans('attribute.confirm.body', ['name' => $attribute->name]) }}">
                                                    <i class="fa fa-fw fa-trash"></i>
                                                    <span
                                                        class="hidden-sm hidden-xs">{{ trans('attribute.index.table.delete') }}</span>
                                                </a>
                                                @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @can('edit', $attribute)
                    <div class="box-footer clearfix">
                        <a href="{{ route('attribute.create', ['model' => $model, 'system' => $system]) }}" class="btn btn-primary">
                            <i class="fa fa-fw fa-plus"></i>
                            <span class="hidden-sm hidden-xs">{{ trans('attribute.index.create') }}</span>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    @endforeach

    @include('partials.confirms.deletion')
@endsection

@push('scripts')
<script>
    $(function () {
        $('.dataTable').DataTable({
            paging:       true,
            lengthChange: false,
            searching:    true,
            ordering:     true,
            info:         false,
            columns:      [
                null,
                {orderable: false},
                {orderable: false, searchable: false}
            ],
            language:     {
                paginate: {
                    previous: '&laquo;',
                    next:     '&raquo;'
                }
            },
            dom:          '<"row"<"col-sm-12"tp>>'
        });

        $('.box-datatable').each(function () {
            $('.box-footer', this).append($('#' + $('.dataTable', this).attr('id') + '_paginate').addClass('pull-right pagination-sm no-margin'));
        });

        $('.data-search').keyup(function () {
            $($(this).data('target')).dataTable().api().search(this.value).draw();
        });
    });
</script>
@endpush
