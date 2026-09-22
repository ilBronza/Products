@foreach($contractDocuments ?? [] as $contractDocument)
	<section style="page-break-before: always;">
		@include($contractDocument['view'])
	</section>
@endforeach
