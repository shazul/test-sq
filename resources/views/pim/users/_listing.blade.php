<table class="table table-bordered table-hover table-users">
    <thead>
        <tr>
            <th>{{ trans('user.index.table.header.name') }}</th>
            <th>{{ trans('user.index.table.header.email') }}</th>
            <th>{{ trans('user.index.table.header.active') }}</th>
            <th>{{ trans('user.index.table.header.groups') }}</th>
            <th>{{ trans('user.index.table.header.companies') }}</th>
            <th>{{ trans('user.index.table.header.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>
                    {{ $user->first_name }} {{ $user->last_name }}
                    @if ($user->id == auth()->user()->id)
                        <span class="badge bg-green">{{ trans('user.index.you') }}</span>
                    @endif
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ($user->active)
                        <i class="fa fa-fw fa-check-circle text-green"></i>
                    @else
                        <i class="fa fa-fw fa-ban text-red"></i>
                    @endif
                </td>
                <td>
                    @foreach ($user->groups as $group)
                        <span class="badge bg-light-blue">{{ $group->name }}</span>
                    @endforeach
                </td>
                <td>
                    {{ implode($user->companies()->get()->pluck('name')->toArray(), ', ') }}
                </td>
                <td class="text-right table-btn nowrap">
                    <div class="btn-group">
                        @can('edit', $user)
                            <a href="{{ route('user.edit', $user) }}" class="btn btn-xs btn-flat btn-warning">
                                <i class="fa fa-fw fa-edit"></i>
                                <span class="hidden-sm hidden-xs">{{ trans('user.index.table.edit') }}</span>
                            </a>
                        @endcan

                        @can('delete', $user)
                            <a href="{{ route('user.destroy', $user) }}" class="btn btn-xs btn-flat btn-danger btn-delete" data-body="{{ trans('user.confirm.body', ['name' => $user->first_name]) }}">
                                <i class="fa fa-fw fa-trash"></i>
                                <span class="hidden-sm hidden-xs">{{ trans('user.index.table.delete') }}</span>
                            </a>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
