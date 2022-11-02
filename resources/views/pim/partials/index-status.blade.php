<ul class="nav nav-tabs">
    @foreach($status_list as $status)
    <li @if ($status['active']) class="active" @endif>
        <a href="{{ $status['link'] }}" aria-expanded="true">
            {{ trans("partial.status.{$status['name']}")}}
            <span data-toggle="tooltip" class="badge bg-blue">{{ $status['nb_products'] }}</span>
        </a>
    </li>
    @endforeach
    <li class="pull-right">
        {!! Form::open(['route' => $route, 'method' => 'GET']) !!}
        {!!
            Form::text(
                'search',
                Input::get('search'),
                ['placeholder' => trans('attribute.index.table.search'),
                'class' => 'form-control pull-right input-sm']
            )
        !!}
        <button type="submit" class="btn btn-primary hidden">Search</button>
        {!! Form::close() !!}
    </li>
</ul>
