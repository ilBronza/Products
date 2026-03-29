@extends('products::html-preview.layout')

@section('content')
@php

	$company = config('pdf.company', []);
	$documentType = $documentTitleDefault;
	$docNumber = $container->getName() ?? '-';
	$clientName = $container->getClient()?->getName() ?? '-';
	$destination = $container->getDestination();
	$destinationAddress = $destination?->address ? trim(implode(', ', array_filter([$destination->address->street ?? null, $destination->address->number ?? null, $destination->address->zip ?? null, $destination->address->town ?? null, $destination->address->city ?? null]))) : '';
	$extraFields = $container->extraFields ?? null;
	$dataEvento = $extraFields->data_evento ?? null;
	$location = $extraFields->location ?? $destinationAddress;
	$pax = $extraFields->pax ?? null;

	$allRows = collect();

	if ($operatorRows->isNotEmpty()) {
		$allRows = $allRows->merge($operatorRows->map(fn($r) => (object)['row' => $r, 'type' => 'operator']));
	}

	if ($productRows->isNotEmpty()) {
		$allRows = $allRows->merge($productRows->map(fn($r) => (object)['row' => $r, 'type' => 'product']));
	}

	if (isset($vehicleRows) && $vehicleRows->isNotEmpty()) {
		$allRows = $allRows->merge($vehicleRows->map(fn($r) => (object)['row' => $r, 'type' => 'vehicle']));
	}

	$allRows = $allRows->sortBy(fn($x) => $x->row->sorting_index ?? 999);

@endphp

@include('products::html-preview._styles')
@include('products::html-preview._content')

@endsection
