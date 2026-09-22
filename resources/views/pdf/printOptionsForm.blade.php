@extends('uikittemplate::app')

@section('content')
	{!! $form->_render() !!}

	<script>
		document.getElementById(@json($form->getId())).target = @json($previewTarget);
	</script>
@endsection
