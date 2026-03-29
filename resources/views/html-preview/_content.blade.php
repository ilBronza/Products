<div class="uk-container uk-container-expand uk-margin-remove-vertical">
	<div class="uk-grid tm-grid-expand uk-flex-top" uk-grid="parallax: 0; parallax-justify: true; parallax-start: 100vh; parallax-end: 100vh;">

		@include('products::html-preview._phases')
		@include('products::html-preview._fullList')
		@include('products::html-preview._images')
	</div>
</div>

@include('products::html-preview._allergensList')

@include('products::html-preview._people')

<div class="uk-margin-large">
	<div class="uk-heading-medium uk-heading-line uk-text-primary uk-position-relative uk-text-right" uk-parallax="x: -10vw,15vw; easing: 0" style="right: 50vw;">
		<span>
			Totale proposto
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
				<ul class="uk-list uk-list-large uk-margin-medium">
					<li class="el-item">
					   <div class="uk-h3 uk-margin-remove" uk-leader>
					   		Pietanze
					   </div>
					   <div class="el-meta uk-text-large uk-text-emphasis uk-text-right">
					   		{!! number_format($container->productRows->sum('total_client_price'), 2, ',', '&#729;') !!} €
					   </div>
					</li>
					<li class="el-item">
					   <div class="uk-h3 uk-margin-remove" uk-leader>
					   		Allestimenti - Personale - Logistica
					   </div>
					   <div class="el-meta uk-text-large uk-text-emphasis uk-text-right">
					   		{!! number_format(($container->getTotalClientPrice() - $container->productRows->sum('total_client_price')), 2, ',', '&#729;') !!} €
					   </div>
					</li>
					<li class="uk-text-right">
						<span class="uk-h2">
							{!! number_format($container->getTotalClientPrice(), 2, ',', '&#729;') !!} €
						</span>						
					</li>
				</ul>
			</div>
			<div class="uk-width-1-3@l"></div>
		</div>
	</div>
</div>
