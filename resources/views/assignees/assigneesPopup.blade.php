<div class="uk-margin-top uk-grid-small" uk-grid>

@foreach($workers as $worker)
<div class="uk-width-small">
    <div class="uk-card-small uk-card uk-card-default">
        <div class="uk-card-header">
            <input class="operatorcheckbox" id="operator{{ $worker->getKey() }}" type="checkbox" name="operator[{{ $worker->getKey() }}]" value="{{ $worker->getKey() }}" />
            <label for="operator{{ $worker->getKey() }}">
                <img style="max-height: 50px;" src="{{ (($avatar = $worker->getAvatarImage()) ? $avatar : null) }}" />
            </label>
        </div>
        <div class="uk-card-body">
            <span class="uk-h3" uk-tooltip="{{ $worker->getFullName() }}">
                <label for="operator{{ $worker->getKey() }}">
                    {{ $worker->getShortName() }}
                </label>
            </span>
        </div>
        <div class="uk-card-footer">
            <span class="uk-h5">{{ $worker->processings->count() }} Processi</span>
            <br />
            <span class="uk-h5">{{ $worker->assignedOrderProductPhases->count() }} Lavorazioni</span>
        </div>
    </div>    
</div>
@endforeach

</div>
