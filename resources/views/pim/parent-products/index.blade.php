@extends('layouts.app')

@section('contentheader_title')
  {{ trans('parent-product.index.title') }}
@endsection

@section('content')
<div class="row">
<div class="col-xs-12">
    <div class="nav-tabs-custom">
        @include('pim.partials.index-status', ['route' => ['parent-product.index', $active_status]])
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="box">
                    <table id="table-parent-products" class="table table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th class="sorting" data-sort="name">{{ trans('parent-product.index.table.header.name') }}</th>
                                <th class="sorting" data-sort="product_nature">{{ trans('parent-product.index.table.header.nature') }}</th>
                                <th class="sorting" data-sort="building_component">{{ trans('parent-product.index.table.header.building-component')}}</th>
                                <th class="sorting" data-sort="media_name">{{ trans('views.medias') }}</th>
                                <th>{{ trans('parent-product.index.table.header.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product_id => $product)
                                <tr>
                                    <td>{{ $product['name'][$lang_code] }}</td>
                                    <td>
                                        @if (isset($product['product_nature'][$lang_code]))
                                        {{ $product['product_nature'][$lang_code] }}
                                        @endif
                                    </td>
                                    <td>@if (isset($product['building_component'][$lang_code])){{ $product['building_component'][$lang_code] }}@endif</td>
                                    <td>@if (isset($product['media_name'])){{ implode(', ', $product['media_name']) }}@endif</td>
                                    <td class="text-right table-btn nowrap">
                                        <div class="btn-group">
                                            @can('edit-parent-product', $product_id)
                                            <a href="{{ route('parent-product.edit', $product_id) }}" class="btn btn-xs btn-flat btn-warning">
                                                <i class="fa fa-fw fa-edit"></i>
                                                <span class="hidden-sm hidden-xs">{{ trans('parent-product.index.table.edit') }}</span>
                                            </a>
                                            @else
                                                <a href="{{ route('parent-product.show', $product_id) }}" class="btn btn-xs btn-flat btn-info">
                                                    <i class="fa fa-fw fa-eye"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('parent-product.index.table.show') }}</span>
                                                </a>
                                            @endcan
                                            @can('delete-parent-product', $product_id)
                                                <a href="{{ route('parent-product.destroy', $product_id) }}"
                                                    class="btn btn-xs btn-flat btn-danger btn-delete"
                                                    data-body="{{ trans('parent-product.confirm.body', ['name' => $product['name'][$lang_code]]) }}"
                                                >
                                                    <i class="fa fa-fw fa-trash"></i>
                                                    <span class="hidden-sm hidden-xs">{{ trans('parent-product.index.table.delete') }}</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($products) == 0)
                                <tr>
                                    <td colspan="5">{{ trans('views.listing.no_results') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {!! $products->links() !!}
                    <div class="box-footer clearfix">
                        @can('create-parent-product')
                            <a href="{{ route('parent-product.create') }}" class="btn btn-primary">
                                <i class="fa fa-fw fa-plus"></i>
                                <span class="hidden-sm hidden-xs">{{ trans('parent-product.index.create') }}</span>
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
