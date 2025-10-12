@extends('uikittemplate::app')


@section('content')

	<div class="uk-card uk-card-default uk-card-small">
	    <div class="uk-card-header">
	        <h3 class="uk-card-title">@lang('products::products.dashboard')</h3>
	    </div>
	    <div class="uk-card-body">
			@include('products::dashboard._dashboard')
		</div>
	</div>

@endsection