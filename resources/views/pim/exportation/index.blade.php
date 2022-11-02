@extends('layouts.app')

@section('contentheader_title')
    {{ trans('exportation.title') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <span>{{ trans('exportation.exportParentProduct') }}:</span>
                    @foreach(get_current_company_languages() as $language)
                         <a class="btn btn-primary" target="_blank" href="{{ URL::route('exportation.exportParent', ['lang' => $language->code]) }}">{{ trans('exportation.' . $language->name) }}</a>
                    @endforeach
                    <br /><br />
                    <h4>{{ trans('exportation.exportAllProduct') }}:</h4>
                    <table cellpadding="10">
                        <tr>
                            <td>{{ trans('exportation.pourTableauxPagesProduit') }}:</td>
                            @foreach(get_current_company_languages() as $language)
                                <td style="padding:5px;"><a class="btn btn-primary" target="_blank" href="{{ URL::route('exportation.exportAll', ['lang' => $language->code, 'role' => 'tableauxPagesProduits']) }}">{{ trans('exportation.' . $language->name) }}</a></td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>{{ trans('exportation.pourTriProduits') }}:</td>
                            @foreach(get_current_company_languages() as $language)
                                <td style="padding:5px;"><a class="btn btn-primary" target="_blank" href="{{ URL::route('exportation.exportAll', ['lang' => $language->code, 'role' => 'trierProduits']) }}">{{ trans('exportation.' . $language->name) }}</a></td>
                            @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

