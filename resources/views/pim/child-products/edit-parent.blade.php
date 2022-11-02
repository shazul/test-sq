@extends('layouts.app')

@section('contentheader_title')
    {{ trans('child-product.edit-parent.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-flat btn-change" href="#"
                            data-href="{{ route('child-product.edit', $child_product->id) }}"
                            data-toggle="modal" data-target="#confirmPageChange">
                            {{ trans('child-product.edit.select-button') }}
                        </a>
                        <a class="btn btn-primary btn-flat disabled" href="#">
                            {{ trans('child-product.edit-parent.select-button') }}
                        </a>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-warning">
                            <h4>
                                {{ trans('child-product.edit-parent.warning-title') }}
                            </h4>
                            {!! trans('child-product.edit-parent.warning-message') !!}
                        </div>
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.confirms.page-change')
@endsection

@push('scripts')
<script>
    $(function () {
        $(".select-2").select2();
    });
</script>
@endpush
