<form method="post" action="{{ registry.uri }}tt/add/?oid={{ oid }}&date={{ now_date }}">

<h2 style="margin: 10px 0 20px 0"><b>Новая задача</b></h2>

<div id="arfiles">
<p style="color: black">
Прикреплённые файлы
<span style="color: green">(<a style="color: green; cursor: pointer" onclick="flushAttaches()">очистить</a>)</span>:
</p>
<div id="attach_files" style="margin-top: 10px"></div>
</div>

<!-- jhtmlarea -->
<div style="overflow: hidden; margin-bottom: 10px">

<div id="text_area" style="float: left">
    <textarea id="jHtmlArea" name="textfield" style="width: 700px; height: 300px"></textarea>
</div>

</div>
<!-- /jhtmlarea -->


<!-- tabs -->
{% include "tt/tabs.tpl" %}
<!-- /tabs -->

<p style="margin-top: 30px">
<input type="submit" name="draft" value="В черновик" style="margin-right: 10px" />
<input type="submit" name="submit" value="Создать" />
</p>

</form>

<div id="usersDialog" title="Выбор пользователей" style="text-align: left"></div>

<script type="text/javascript">
htmlarea();

{% if issRusers %}
{% for part in issRusers %}
$("#addedusers").append('{{ part.desc }}');
{% endfor %}
{% endif %}

function flushAttaches() {
	$("#attach_files").html('');
};
</script>