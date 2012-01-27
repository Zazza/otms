<p>Название: <input type="text" name="title" id="title" style="width: 300px" /></p>

<!-- jhtmlarea -->
<div style="overflow: hidden; margin-bottom: 10px">

	<div id="text_area" style="float: left">
		<textarea id="jHtmlArea" name="textfield" style="width: 700px; height: 300px">{{ post.textfield }}</textarea>
	</div>

</div>
<!-- /jhtmlarea -->

<p>Теги: <input type="text" name="tags" id="tags" style="width: 300px" /></p>

<div style="width: 500px">
	<input type="submit" data-inline="true" onclick="addAINote()" name="submit" value="Добавить" />
</div>

<script type="text/javascript">
	htmlarea();
	
	function addAINote() {
	    var data = "action=addAdvancedNote&title=" + $("#title").val() + "&text=" + $("#jHtmlArea").htmlarea('toHtmlString') + "&tags=" + $("#tags").val();
		$.ajax({
			type: "POST",
			url: "{{ registry.uri }}ajax/tt/",
			data: data,
			success: function(res) {
	            document.location.href = "{{ registry.uri }}kb/";
			}
		});
	}
</script>