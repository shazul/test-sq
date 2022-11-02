@extends('layouts.app')

@section('contentheader_title')
  {{ trans('child-product.index.title') }}
@endsection

@section('content')
<div class="row">
<div class="col-xs-12">
    <div class="nav-tabs-custom">
        @include('pim.partials.index-status', ['route' => ['child-product.index', $active_status]])
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="box">
                    <div class="box-body">
                        <table id="table-child-products" class="table table-bordered table-hover dataTable">
                            <thead>
                                <tr>
                                    <th class="sorting" data-sort="child_product_code">{{ trans('child-product.index.table.header.product_code') }}</th>
                                    <th class="sorting" data-sort="child_product_name">{{ trans('child-product.index.table.header.name') }}</th>
                                    <th class="sorting" data-sort="product_nature">{{ trans('child-product.index.table.header.nature') }}</th>
                                    <th class="sorting" data-sort="name">{{ trans('child-product.index.table.header.parent') }}</th>
                                    <th class="sorting" data-sort="media_name">{{ trans('views.medias') }}</th>
                                    <th>{{ trans('child-product.index.table.header.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product_id => $product)
                                    <tr>
                                        <td>@if (isset($product['child_product_code'])){{ $product['child_product_code'] }}@endif</td>
                                        <td>{{ $product['child_product_name'][$current_language->code] }}</td>
                                        <td>@if (isset($product['product_nature'])){{ $product['product_nature'] }}@endif</td>
                                        <td>@if (isset($product['name'])){{ $product['name'][$current_language->code] }}@endif</td>
                                        <td>@if (isset($product['media_name'])){{ implode(', ', $product['media_name']) }}@endif</td>
                                        <td class="text-right table-btn nowrap">
                                            @include('pim.child-products.index-action')
                                        </td>
                                    </tr>
                                @endforeach
                                @if(count($products) == 0)
                                    <tr>
                                        <td colspan="6">{{ trans('views.listing.no_results') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {!! $products->links() !!}
                    <div class="box-footer clearfix">
                        @can('create-child-product')
                            <a href="{{ route('child-product.create') }}" class="btn btn-primary">
                                <i class="fa fa-fw fa-plus"></i>
                                <span class="hidden-sm hidden-xs">{{ trans('child-product.index.create') }}</span>
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
