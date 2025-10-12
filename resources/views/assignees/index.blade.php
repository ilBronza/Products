@extends('uikittemplate::app')

@section('content')

<script type="text/javascript">

jQuery(document).ready(function($)
{
    window.assignOperators = function(operators)
    {
        $('.uk-lightbox-toolbar-icon.uk-close').click();

        var orderProductPhases = [];

        let workstationFetcher;

        $('.oppcheckbox:checkbox:checked').each(function()
        {
            workstationFetcher = $(this).closest('.ibfetchercontainer');
            orderProductPhases.push($(this).val());
        });

        $.ajax({
            url : '{{ app('products')->route('orderProductPhases.assignees.setAssignees') }}',
            method : 'POST',
            data : {
                orderProductPhases : orderProductPhases,
                operators : operators
            },
            success : function(response)
            {
                $(workstationFetcher).find('.refresh').click();
                $('.assigneeslist').find('.refresh').click();
            },
            error: function(response)
            {
                alert('error');
            }
        });
    }

    window.removeOtherCheckboxByTarget = function(target)
    {
        $(target).closest('.workstation').siblings('.workstation').each(function()
        {
            $(this).find('input:checkbox').each(function()
            {
                $(this).prop('checked', false); 
            });
        });
    }

    $("body").on('click', '.selectalloperators', function()
    {
        $(this).closest('.workstation').find('input:checkbox').each(function()
        {
            $(this).prop('checked', 'checked');
        });        
    });

    $("body").on('click', '.unselectalloperators', function()
    {
        $(this).closest('.workstation').find('input:checkbox').each(function()
        {
            $(this).prop('checked', false);
        });        
    });

    $("body").on('click', '.removeoperator', function()
    {
        var orderProductPhases = [];

        let workstationFetcher;

        $('.oppcheckbox:checkbox:checked').each(function()
        {
            workstationFetcher = $(this).closest('.ibfetchercontainer');
            orderProductPhases.push($(this).val());
        });

        $.ajax({
            url : '{{ app('products')->route('orderProductPhases.assignees.removeAssignees') }}',
            method : 'POST',
            data : {
                orderProductPhases : orderProductPhases
            },
            success : function(response)
            {
                $(workstationFetcher).find('.refresh').click();
                $(workstationFetcher).find('.refresh').click();
            },
            error: function(response)
            {
                alert('error');
            }
        });

    });

    $('body').on('change', 'input', function()
    {
        window.removeOtherCheckboxByTarget(this);
    });

    $('body').on('click', '.associateassignees', function()
    {
        let checkedOperators = [];

        $('.operatorcheckbox:checkbox:checked').each(function()
        {
            checkedOperators.push($(this).val());
        });

        window.parent.assignOperators(checkedOperators);
    });
});
</script>

@if(($user = Auth::user())&&($user->hasAnyRole('administrator', 'organizator')))
<div class="assigneeslist">
    
{!! app('fetcher')
    ->setUrl(
        app('products')
            ->route(
                'orderProductPhases.assignees.popup'
            )
        )
    ->renderCard() !!}

</div>

<div class="uk-padding">

    <button id="associateassignees" class="associateassignees uk-button uk-button-primary">
        Associa
    </button>

    <a class="removeoperator uk-button uk-button-primary" href="javascript:void(0)"><i class="fa-solid fa-remove"></i> Rimuovi operatori</a>
</div>

@endif

<div class="uk-grid-collapse" uk-grid>

    @foreach($workstations as $workstation)
    <div class="workstation">
        {!! app('fetcher')
            ->setUrl(
                app('products')
                    ->route(
                        'orderProductPhases.assignees.workstationFetcher',
                        ['workstation' => $workstation->getAlias()]
                    )
                )
            ->renderCard() !!}
    </div>
    @endforeach

</div>

@endsection
