<ol class="breadcrumb">
    @foreach ($crumbs as $crumb)
        <li
            @if ($crumb->current)
                class="active"
            @endif
        >
            @if (!$crumb->current)
                <a href="{{ $crumb->link }}">
            @endif

            @if ($crumb->icon)
                <i class="fa fa-{{ $crumb->icon }}"></i>
            @endif
            {{ $crumb->title }}

            @if (!$crumb->current)
                </a>
            @endif
        </li>
    @endforeach
</ol>
