@foreach($items as $item)
    @if ($item->header)
        <li class="header"><span>{{ trans($item->text) }}</span></li>
    @else
        @if ($item->count() > 0)
            <li class="treeview{{ $item->isActive() ? ' active': '' }}">
        @else
            <li{!! $item->isActive() ? ' class="active"' : '' !!}>
        @endif
            <a href="{{ route($item->route, $item->parameters) }}">
                <i class="fa fa-{{ $item->icon }}"></i>
                <span>{{ trans($item->text) }}</span>
                @if ($item->count() > 0)
                    <i class="fa fa-angle-left pull-right"></i>
                @endif
            </a>
            @if ($item->count() > 0)
                <ul class="treeview-menu">
                    @include('partials.mainmenu.custom-menu-items', ['items' => $item->all()])
                </ul>
            @endif
        </li>
    @endif
@endforeach
