@if(count($orderProductPhases))
<div class="uk-width-xlarge">
    <div class="uk-card uk-card-small uk-card-default">
        <div class="uk-card-header">
            <span class="uk-h3">{{ $workstation->getName() }}</span>
            @if(($user = Auth::user())&&($user->hasAnyRole('administrator', 'organizator')))
            <div>
                <a class="selectalloperators uk-margin-right"><i class="fa-solid fa-square-check"></i> Seleziona tutti</a>
                <a class="unselectalloperators"><i class="fa-solid fa-square"></i> Deseleziona tutti</a>                
            </div>
            @endif
        </div>
        <div class="uk-card-body">
            <table class="uk-width-1-1 uk-table-striped">
                @foreach($orderProductPhases as $orderProductPhase)
                    @if($loop->index < 10)
                    <tr>
                        @if(($user = Auth::user())&&($user->hasAnyRole('administrator', 'organizator')))
                        <td>
                            <input class="oppcheckbox" id="opp{{ $orderProductPhase->getKey() }}" type="checkbox" value="{{ $orderProductPhase->getKey() }}" />
                        </td>
                        @endif
                        <td>
                            <label for="opp{{ $orderProductPhase->getKey() }}">
                                {{ $orderProductPhase->getWorkstationId() }}
                            </label>
                        </td>
                        <td>
                            <a href="{{ $orderProductPhase->getOrder()?->getOldShowOrderUrl() }}">
                                {{ $orderProductPhase->getOrder()?->getName() }}
                            </a>
                        </td>
                        <td>
                            <label for="opp{{ $orderProductPhase->getKey() }}">
                                {{ Str::limit($orderProductPhase->getOrder()?->getClient()?->getName(), 14, '') }}
                            </label>
                        </td>
                        <td>
                            <label for="opp{{ $orderProductPhase->getKey() }}">
                                {{ $orderProductPhase->getProduct()?->getName() }}
                            </label>
                        </td>
                        <td>
                            <label for="opp{{ $orderProductPhase->getKey() }}">
                                {{ $orderProductPhase->getQuantityRequired() }}
                            </label>
                        </td>
                        <td>
                            <label for="opp{{ $orderProductPhase->getKey() }}">
                                {{ $orderProductPhase->getTimingEstimation()?->getMachineTotalSeconds() }}
                            </label>
                        </td>
                        <td>
                            <label class="uk-text-nowrap uk-text-danger uk-text-bold" for="opp{{ $orderProductPhase->getKey() }}">
                                @foreach($orderProductPhase->getOrderProduct()?->getDeliveries() ?? [] as $delivery)
                                    {{ $delivery->datetime?->format(trans('dates.humanShortDayTime')) }}
                                @endforeach
                            </label>
                        </td>
                        <td>
                            <label class="uk-text-bold" for="opp{{ $orderProductPhase->getKey() }}">
                                @forelse($orderProductPhase->assignees as $assignee)
                                {{ $assignee->getShortName() }}
                                @empty
                                 - 
                                @endforelse
                            </label>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
</div>
@endif
