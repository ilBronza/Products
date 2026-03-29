@if(count($allergens = $container->getAllergensList()) > 0)

<div class="uk-margin-large">
	<div class="uk-heading-medium uk-heading-line uk-text-primary uk-position-relative uk-text-right" uk-parallax="x: -10vw,15vw; easing: 0" style="right: 50vw;">
		<span>
			Allergeni nella proposta
		</span>    
	</div>

	<div class="uk-container uk-container-expand uk-margin-remove-vertical">
		<div 
			class="uk-grid tm-grid-expand uk-flex-top" 
			uk-grid="parallax: 0; parallax-justify: true; parallax-start: 100vh; parallax-end: 100vh;"
			>
			<div class="js-sticky uk-width-1-5@xl uk-visible@xl uk-first-column" style="align-self: stretch; transform: translate(0px, 0px);">
			</div>
			<div class="uk-width-expand">
				<ul class="uk-list">
				@foreach($allergens as $allergen)
					<li>
						<span class="uk-h4">
							{{ $allergen->getName() }}
						</span>
					</li>
				@endforeach
				</ul>
			</div>
			<div class="uk-width-1-3@l"></div>
		</div>
	</div>
</div>

@endif
