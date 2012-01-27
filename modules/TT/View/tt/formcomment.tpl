<h3>Новый комментарий:</h3>

<div id="arfiles">
<p style="color: black">
Прикреплённые файлы
<span style="color: green">(<a style="color: green; cursor: pointer" onclick="flushAttaches()">очистить</a>)</span>:
</p>
<div id="attach_files" style="margin-top: 10px"></div>
</div>

<div style="overflow: hidden">
<div id="text_area" style="float: left">
    <textarea id="jHtmlArea" name="textfield" style="width: 700px; height: 300px"></textarea>
</div>
</div>

<p>
<b>Статус:</b>
<select id="status" name="status" style="margin-left: 10px">
<option value="0">Нет</option>
{% for part in status %}
<option value="{{ part.id }}">{{ part.status }}</option>
{% endfor %}
</select>
</p>

<div style="clear: both; padding-top: 10px"><input type="button" onclick="addComment()" value="Написать" /></div>

<script type="text/javascript">
htmlarea();

function addComment() {
	var formData = new Array(); var i = 0;
	$("input[name='attaches[]']").each(function(n){
		name = i;
		val = this.value;

		formData[i] = ['"' + name + '"', '"' + val + '"'].join(":");

		i++;
	});

	var json = "{" + formData.join(",") + "}";
	
	var data = "action=addComment&tid={{ tid }}&text=" + encodeURIComponent($("#jHtmlArea").htmlarea('toHtmlString')) + "&status=" + $("#status").val() + "&json=" + json;
	$.ajax({
		type: "POST",
    	url: "{{ registry.uri }}ajax/tt/",
    	data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	});
};
</script>