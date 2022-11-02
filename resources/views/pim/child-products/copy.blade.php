<div class="box box-default{{ !$errors->has('child_product_id') ? ' collapsed-box' : '' }}">
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('child-product.edit.copy-children') }}</h3>
        <div class="box-tools pull-right">
            <button type="button" data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-{{ $errors->has('child_product_id') ? 'minus' : 'plus' }}"></i></button>
        </div>
    </div>

    <div class="box-body">
        @if (count($other_children) === 0)
            <div class="form-group">
                <label>{{ trans('child-product.edit.copy-no-children') }}</label>
            </div>
        @else
            {!! Form::open(['route' => ['child-product.copy-attribute', $child_product->id]]) !!}
                <div class="form-group{{ $errors->has('child_product_id') ? ' has-error' : '' }}">
                    <label>{{ trans('child-product.edit.copy-attribute') }}</label>
                    <select name="child_product_id" class="select-2 form-control" style="width: 100%;">
                        <option value="0">{{ trans('child-product.edit.select-child') }}</option>
                        @foreach ($other_children as $child)
                            <option value="{{ $child['id'] }}">{{ $child['code'] }} {{ $child['text'][auth()->user()->getLanguageCode()] }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('child_product_id'))
                        <span class="help-block">{{ $errors->first('child_product_id') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-primary" data-target="#confirmCopy" data-toggle="modal">
                        <i class="fa fa-fw fa-files-o"></i>
                        {{ trans('child-product.edit.replace-attributes') }}
                    </button>
                </div>
            {!! Form::close() !!}
        @endif
    </div>
</div>

@include('partials.confirms.copy')
