<div class="btn-group">
    @if ($active_status == 'fresh')
        @can('approve-child-product', $product_id)
            <a href="{{ route('child-product.approve', $product_id) }}" class="btn btn-xs btn-flat btn-info">
                <i class="fa fa-fw fa-edit"></i>
                <span class="hidden-sm hidden-xs">{{ trans('child-product.index.review-approbation') }}</span>
            </a>
        @endcan
    @else
        @can('edit-child-product', $product_id)
            @if ($active_status == 'parentless')
            <a href="{{ route('child-product.edit-parentless', $product_id) }}" class="btn btn-xs btn-flat btn-warning">
            @else
            <a href="{{ route('child-product.edit', $product_id) }}" class="btn btn-xs btn-flat btn-warning">
            @endif
                <i class="fa fa-fw fa-edit"></i>
                <span class="hidden-sm hidden-xs">{{ trans('child-product.index.table.edit') }}</span>
            </a>
        @else
            <a href="{{ route('child-product.show', $product_id) }}" class="btn btn-xs btn-flat btn-info">
                <i class="fa fa-fw fa-eye"></i>
                <span class="hidden-sm hidden-xs">{{ trans('child-product.index.table.show') }}</span>
            </a>
        @endcan
        @can('delete-child-product', $product_id)
            <a href="{{ route('child-product.destroy', $product_id) }}" class="btn btn-xs btn-flat btn-danger btn-delete"
                data-body="{{ trans('child-product.confirm.body', ['name' => $product['child_product_name'][$current_language->code]]) }}">
                <i class="fa fa-fw fa-trash"></i>
                <span class="hidden-sm hidden-xs">{{ trans('child-product.index.table.delete') }}</span>
            </a>
        @endcan
    @endif
</div>
