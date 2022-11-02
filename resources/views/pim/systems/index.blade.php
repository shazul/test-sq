@extends('layouts.app')

@section('contentheader_title')
  {{ trans('system.index.title') }}
@endsection

@section('content')
<div class="row">
<div class="col-xs-12">
    <div class="nav-tabs-custom">
        @include('pim.partials.index-status', ['route' => ['system.index', $active_status]])
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="box">
                    <table id="table-systems" class="table table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th class="sorting" data-sort="system_name">{{ trans('system.index.table.header.name') }}</th>
                                <th class="sorting" data-sort="media_name">{{ trans('views.medias') }}</th>
                                <th class="sorting" data-sort="building_component">{{ trans('system.index.table.header.building-component') }}</th>
                                <th>{{ trans('system.index.table.header.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($systems as $system_id => $system)
                                <tr>
                                    <td>{{ $system['system_name'][$current_language->code] }}</td>
                                    <td>@if (isset($system['media_name'])){{ implode($system['media_name'], ', ') }}@endif</td>
                                    <td>@if (isset($system['building_component'] )){{ implode($system['building_component'], ', ') }}@endif</td>
                                    <td class="text-right table-btn nowrap">
                                        <div class="btn-group">
                                            @can('edit-system', $system_id)
                                                <a href="{{ route('system.edit', [$system_id]) }}" class="btn btn-xs btn-flat btn-warning">
                                                    <i class="fa fa-fw fa-edit"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('system.index.table.edit') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('system.show', [$system_id]) }}" class="btn btn-xs btn-flat btn-info">
                                                    <i class="fa fa-fw fa-eye"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('system.index.table.show') }}</span>
                                                </a>
                                            @endcan
                                            @can('delete-system', $system_id)
                                                <a href="{{ route('system.destroy', $system_id) }}" class="btn btn-xs btn-flat btn-danger btn-delete" data-body="{{ trans('system.confirm.body', ['name' => $system['system_name'][$current_language->code]]) }}">
                                                    <i class="fa fa-fw fa-trash"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('system.index.table.delete') }}</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($systems) == 0)
                                <tr>
                                    <td colspan="3">{{ trans('views.listing.no_results') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {!! $systems->links() !!}
                    <div class="box-footer clearfix">
                        @can('create-system')
                            <a href="{{ route('system.create') }}" class="btn btn-primary">
                                <i class="fa fa-fw fa-plus"></i>
                                <span class="hidden-sm hidden-xs">{{ trans('system.index.create') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>
            </div> <!-- /.tab-pane -->
        </div> <!-- /.tab-content -->
    </div> <!-- /.nav-tabs-custom -->
</div>
</div>
@include('partials.confirms.deletion')
@endsection

@include('pim.partials.sortable-scripts')
