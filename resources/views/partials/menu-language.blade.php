@if ($view_languages->count())
    <div class="dropdown dropdown-language">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
            <span class="fa fa-globe" aria-hidden="true"></span>{{ trans('menu.language_switcher') }}
        </button>
        <ul class="dropdown-menu">
            @foreach ($view_languages as $language)
                <li>
                    <a href="{{ route('user.change.language', ['language_id' => $language->id]) }}">{{$language->name}}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif