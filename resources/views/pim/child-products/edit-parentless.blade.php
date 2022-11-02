@extends('layouts.app')

@section('contentheader_title')
    {{ trans('child-product.edit-parent.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="box-body">
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
