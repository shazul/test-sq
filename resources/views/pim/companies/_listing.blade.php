<table class="table table-bordered table-hover table-companies">
    <thead>
        <tr>
            <th>{{ trans('company.index.table.header.name') }}</th>
            <th>{{ trans('company.index.table.header.default-language') }}</th>
            <th>{{ trans('company.index.table.header.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($companies as $company)
        <tr>
            <td>
                {{ $company->name }}
            </td>
            <td>{{ $company->defaultLanguage->name }}</td>
            <td class="text-right table-btn nowrap">
                <div class="btn-group">
                    @can('edit', $company)
                        <a href="{{ route('company.edit', $company) }}" class="btn btn-xs btn-flat btn-warning">
                            <i class="fa fa-fw fa-edit"></i>
                            <span class="hidden-sm hidden-xs">{{ trans('company.index.table.edit') }}</span>
                        </a>
                    @endcan

                    @if($company->isDeletable())
                        @can('delete', $company)
                            <a href="{{ route('company.destroy', $company) }}"
                                class="btn btn-xs btn-flat btn-danger btn-delete"
                                data-body="{{ trans('company.confirm.body', ['name' => $company->name]) }}"
                            >
                                <i class="fa fa-fw fa-trash"></i>
                                <span class="hidden-sm hidden-xs">{{ trans('company.index.table.delete') }}</span>
                            </a>
                        @endcan
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
