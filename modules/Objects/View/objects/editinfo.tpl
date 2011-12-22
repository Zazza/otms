<h3>Информация:</h3>

<p>Название: <input type="text" name="title" id="title" style="width: 300px" value="{{ ai.adv.title }}" /></p>

<div id="text_area">
    <textarea id="jHtmlArea" name="textfield" style="width: 700px; height: 300px">{{ ai.adv.val }}</textarea>
</div>

<p>Теги: <input type="text" name="tags" id="tags" style="width: 300px" value="{{ ai.tags }}" /></p>

<div style="clear: both; padding-top: 10px"><input type="button" onclick="editAI()" value="Изменить" /></div>

<script type="text/javascript">
htmlarea();

function editAI() {
    var data = "action=editAdvanced&oid={{ aoid }}&title=" + $("#title").val() + "&text=" + $("#jHtmlArea").htmlarea('toHtmlString') + "&tags=" + $("#tags").val();
	$.ajax({
		type: "POST",
		url: url + "ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = "{{ registry.uri }}kb/";
		}
	})
}
</script>