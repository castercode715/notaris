<div id="rating"></div>

<script>
	$("#rating").load("{{ route('asset.rating-pane', $model->id) }}");
</script>