<script type="text/javascript">
jQuery(document).ready(function ($)
{
	
	@foreach($modelInstance->fieldsToUpdateOnTableEdit as $field)
		window.dtEditorRefreshingFieldList.push('input[name={{ $field }}]');
	@endforeach

});
</script>