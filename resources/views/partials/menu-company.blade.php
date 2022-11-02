@if ($view_companies->count())
    <div class="dropdown dropdown-company">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
            <span class="fa fa-building" aria-hidden="true"></span>{{ trans('menu.company_switcher') }}
        </button>
        <ul class="dropdown-menu">
            @foreach ($view_companies as $company)
            <li><a href="{{ route('user.change.company', ['company' => $company->id]) }}">{{ $company->name }}</a></li>
            @endforeach
        </ul>
    </div>
@endif