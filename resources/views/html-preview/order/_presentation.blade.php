{{-- Parte emozionale di presentazione --}}
<div class="hero-gradient uk-padding-large uk-flex uk-flex-between uk-flex-middle uk-flex-wrap" uk-grid>
	<div class="uk-width-1-1 uk-width-expand@m">
		<span class="company-badge">{{ $company['name'] ?? config('clients.ownCompanyName', '') }}</span>
		<div class="uk-margin-small-top" style="color: var(--text-muted); font-size: 0.9rem;">
			{{ $company['address'] ?? '' }} &middot;
			Tel. {{ $company['phone'] ?? '' }}
			@if(!empty($company['email'])) &middot; {{ $company['email'] }} @endif
		</div>
	</div>
	<div class="uk-width-1-1 uk-width-auto@m uk-text-right@m">
		<div class="doc-badge">{{ $documentType }} {{ $docNumber }}</div>
	</div>
</div>

<div class="uk-container uk-container-large content-wrap uk-padding">
	<div class="uk-grid-large" uk-grid>
		<div class="uk-width-1-1 uk-width-1-3@m">
			<div class="info-card uk-margin-medium-bottom">
				<h3>Destinatario</h3>
				<p class="uk-margin-remove uk-text-large" style="font-weight: 600;">{{ $clientName }}</p>
			</div>
			@if($dataEvento || $location || $pax)
			<div class="info-card">
				<h3>Dettagli Evento</h3>
				@if($dataEvento)
					<p class="uk-margin-small"><strong>Data:</strong> {{ \Carbon\Carbon::parse($dataEvento)->format('d F Y') }}</p>
				@endif
				@if($location)
					<p class="uk-margin-small"><strong>Location:</strong> {{ $location }}</p>
				@endif
				@if($pax)
					<p class="uk-margin-small"><strong>Pax:</strong> {{ $pax }}</p>
				@endif
			</div>
			@endif
		</div>
		<div class="uk-width-1-1 uk-width-2-3@m">
