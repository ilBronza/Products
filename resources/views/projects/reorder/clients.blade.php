@extends('uikittemplate::app')

@section('content')

<div class="uk-container uk-container-large uk-padding">
    <div class="uk-flex uk-flex-between uk-flex-middle uk-margin">
        <h3 class="uk-margin-remove">@lang('products::products.projects')</h3>
    </div>

    <div class="uk-card uk-card-default uk-card-body">
        <h4 class="uk-card-title uk-margin-small">@lang('products::fields.client')</h4>

        <div class="uk-overflow-auto">
            <table class="uk-table uk-table-small uk-table-divider uk-table-hover">
                <thead>
                <tr>
                    <th>@lang('products::fields.name')</th>
                    <th class="uk-table-shrink"></th>
                    <th class="uk-table-shrink"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>
                            {{ $client->getName() ?? $client->name ?? $client->getKey() }}
                        </td>
                        <td>{{ $client->projects_count }}</td>
                        <td class="uk-text-right">
                            <a class="uk-button uk-button-primary uk-button-small"
                               href="{{ app('products')->route('projects.reorder.byClient', ['client' => $client->getKey()]) }}">
                                Riordina
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

