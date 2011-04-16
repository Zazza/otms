<p><b>Шаблон</b></p>
<p>
<select name="templates" id="templates">
{% for part in list %}
<option value="{{ part.id }}">{{ part.name }}</option>
{% endfor %}
</select>
<input type="button" name="seltpl" id="seltpl" value="Выбрать" onclick="seltpl()" />
</p>

<div id="fields"></div>

<script type="text/javascript">
function seltpl() {
    var data = "action=getTemplateFields&id=" + $("#templates").val();
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
            $("#fields").html(res);
		}
	})
}
</script>