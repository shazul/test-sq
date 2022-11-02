@extends('layouts.app')

@section('contentheader_title')
  {{ trans('specification.index.title') }}
@endsection

@section('content')
<div class="row">
<div class="col-xs-12">
    <div class="nav-tabs-custom">
        @include('pim.partials.index-status', ['route' => ['specification.index', $active_status]])
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="box">
                    <table id="table-specifications" class="table table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th class="sorting" data-sort="spec_name">{{ trans('specification.index.table.header.name') }}</th>
                                <th class="sorting" data-sort="spec_building_component">{{ trans('specification.index.table.header.building-component') }}</th>
                                <th class="sorting" data-sort="media_name">{{ trans('views.medias') }}</th>
                                <th>{{ trans('specification.index.table.header.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($specs as $spec_id => $specification)
                                <tr>
                                    <td>{{ $specification['spec_name'][$current_language->code] }}</td>
                                    <td>{{ $specification['spec_building_component'][$current_language->code] }}</td>
                                    <td>@if (isset($specification['media_name'])){{ implode(', ', $specification['media_name']) }}@endif</td>
                                    <td class="text-right table-btn nowrap">
                                        <div class="btn-group">
                                            @can('edit-specification', $spec_id)
                                                <a href="{{ route('specification.edit', $spec_id) }}" class="btn btn-xs btn-flat btn-warning">
                                                    <i class="fa fa-fw fa-edit"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('specification.index.table.edit') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('specification.show', $spec_id) }}" class="btn btn-xs btn-flat btn-info">
                                                    <i class="fa fa-fw fa-eye"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('specification.index.table.show') }}</span>
                                                </a>
                                            @endcan
                                            @can('delete-specification', $spec_id)
                                                <a href="{{ route('specification.destroy', $spec_id) }}" class="btn btn-xs btn-flat btn-danger btn-delete" data-body="{{ trans('specification.confirm.body', ['name' => $specification['spec_name'][$current_language->code]]) }}">
                                                    <i class="fa fa-fw fa-trash"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('specification.index.table.delete') }}</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($specs) == 0)
                                <tr>
                                    <td colspan="4">{{ trans('views.listing.no_results') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {!! $specs->links() !!}
                    <div class="box-footer clearfix">
                        @can('create-specification')
                            <a href="{{ route('specification.create') }}" class="btn btn-primary">
                                <i class="fa fa-fw fa-plus"></i>
                                <span class="hidden-sm hidden-xs">{{ trans('specification.index.create') }}</span>
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
