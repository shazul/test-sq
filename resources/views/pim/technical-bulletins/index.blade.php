@extends('layouts.app')

@section('contentheader_title')
  {{ trans('technical-bulletin.index.title') }}
@endsection

@section('content')
<div class="row">
<div class="col-xs-12">
    <div class="nav-tabs-custom">
        @include('pim.partials.index-status', ['route' => ['technical-bulletin.index', $active_status]])
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="box">
                    <table id="table-technical-bulletins" class="table table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th class="sorting" data-sort="technical_bulletin_name">{{ trans('technical-bulletin.index.table.header.name') }}</th>
                                <th class="sorting" data-sort="technical_bulletin_building_component">{{ trans('technical-bulletin.index.table.header.building-component') }}</th>
                                <th class="sorting" data-sort="media_name">{{ trans('views.medias') }}</th>
                                <th>{{ trans('technical-bulletin.index.table.header.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bulletins as $bulletin_id => $bulletin)
                                <tr>
                                    <td>{{ $bulletin['technical_bulletin_name'][$current_language->code] }}</td>
                                    <td>{{ $bulletin['technical_bulletin_building_component'][$current_language->code] }}</td>
                                    <td>@if (isset($bulletin['media_name'])){{ implode(', ', $bulletin['media_name']) }}@endif</td>
                                    <td class="text-right table-btn nowrap">
                                        <div class="btn-group">
                                            @can('edit-technical-bulletin', $bulletin_id)
                                                <a href="{{ route('technical-bulletin.edit', $bulletin_id) }}" class="btn btn-xs btn-flat btn-warning">
                                                    <i class="fa fa-fw fa-edit"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('technical-bulletin.index.table.edit') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('technical-bulletin.show', $bulletin_id) }}" class="btn btn-xs btn-flat btn-info">
                                                    <i class="fa fa-fw fa-eye"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('technical-bulletin.index.table.show') }}</span>
                                                </a>
                                            @endcan
                                            @can('delete-technical-bulletin', $bulletin_id)
                                                <a href="{{ route('technical-bulletin.destroy', $bulletin_id) }}" class="btn btn-xs btn-flat btn-danger btn-delete" data-body="{{ trans('technical-bulletin.confirm.body', ['name' => $bulletin['technical_bulletin_name'][$current_language->code]]) }}">
                                                    <i class="fa fa-fw fa-trash"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('technical-bulletin.index.table.delete') }}</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($bulletins) == 0)
                                <tr>
                                    <td colspan="4">{{ trans('views.listing.no_results') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                {!! $bulletins->links() !!}
                <div class="box-footer clearfix">
                    @can('create-technical-bulletin')
                        <a href="{{ route('technical-bulletin.create') }}" class="btn btn-primary">
                            <i class="fa fa-fw fa-plus"></i>
                            <span class="hidden-sm hidden-xs">{{ trans('technical-bulletin.index.create') }}</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @include('partials.confirms.deletion')
@endsection

@include('pim.partials.sortable-scripts')
