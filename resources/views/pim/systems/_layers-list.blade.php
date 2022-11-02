<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('system.layers.title') }}</h3>
    </div>
    <div class="box-body">
        @forelse ($layerGroups as $layerGroup)
        <div class="panel box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $layerGroup->position }}. {{ $layerGroup->name[$current_language->code] }}</h3>
                @can('edit-system')
                <a href="{{ route('system.edit-layer-group', $layerGroup->id) }}" class="btn btn-xs btn-flat btn-warning">
                    <i class="fa fa-fw fa-edit"></i>
                    <span class="hidden-sm hidden-xs">{{ trans('system.index.table.edit') }}</span>
                </a>
                @endcan
                @can('delete-system')
                <a href="{{ route('system.delete-layer-group', $layerGroup->id) }}" class="btn btn-xs btn-flat btn-danger btn-delete"
                    data-body="{{ trans('system.layer-groups.delete-confirm', ['name' => $layerGroup->product_name[$current_language->code]]) }}"
                >
                    <i class="fa fa-fw fa-trash"></i>
                    <span class="hidden-sm hidden-xs">{{ trans('system.index.table.delete') }}</span>
                </a>
                @endcan
            </div>
            @can('create-system')
                <a href="{{ route('system.add-layer', $layerGroup->id) }}" class="btn btn-primary">
                    <i class="fa fa-fw fa-plus"></i>
                    {{ trans('system.layers.add') }}
                </a>
            @endcan
            <div>
            <table class="table">
                <tbody><tr>
                    <th style="width: 10px">Position</th>
                    <th>Product Name</th>
                    <th>Product Function</th>
                    <th>{{ trans('system.index.table.header.actions') }}</th>
                </tr>
                @forelse ($layerGroup->layers as $layer)
                <tr>
                    <td>{{ $layer->position }}</td>
                    <td>{{ $layer->product_name[$current_language->code] }}</td>
                    <td>{{ $layer->product_function[$current_language->code] }}</td>
                    <td>
                        <div class="btn-group">
                            @can('edit-system')
                            <a href="{{ route('system.edit-layer', $layer->id) }}" class="btn btn-xs btn-flat btn-warning">
                                <i class="fa fa-fw fa-edit"></i>
                                <span class="hidden-sm hidden-xs">{{ trans('system.index.table.edit') }}</span>
                            </a>
                            @endcan
                            @can('delete-system')
                            <a href="{{ route('system.delete-layer', $layer->id) }}" class="btn btn-xs btn-flat btn-danger btn-delete"
                                data-body="{{ trans('system.layer.deleteConfirm', ['name' => $layer->product_name[$current_language->code]]) }}"
                            >
                                <i class="fa fa-fw fa-trash"></i>
                                <span class="hidden-sm hidden-xs">{{ trans('system.index.table.delete') }}</span>
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">{{ trans('system.layers.no-layers') }}</td>
                </tr>
                @endforelse
              </tbody></table>
            </div>
        </div>
        @empty
        <div>{{ trans('system.layer-groups.no-layers') }}</div>
        @endforelse
    </div>
    @include('partials.confirms.deletion')
</div>
