<form method="post" action="{{ registry.uri }}tt/edit/">

<input type="hidden" name="tid" value="{{ data.0.id }}" />

<h2 style="margin: 10px 0 20px 0"><b>Правка задачи</b></h2>

<div id="arfiles">
<p style="color: black">
Прикреплённые файлы
<span style="color: green">(<a style="color: green; cursor: pointer" onclick="flushAttaches()">очистить</a>)</span>:
</p>
<div id="attach_files" style="margin-top: 10px"></div>
</div>

<!-- jhtmlarea -->
<div style="overflow: hidden; margin-bottom: 10px">

{% if not data.0.mail_id %}
<div id="text_area" style="float: left">
    <textarea id="jHtmlArea" name="textfield" style="width: 700px; height: 300px">{{ data.0.text }}</textarea>
</div>
{% else %}
<iframe style='height: 300px; border: 1px solid #B6B6B6' class="mailtext" src="{{ registry.siteName }}{{ registry.uri }}mail/load/?mid={{ data.0.mail_id }}&part=1" frameborder="0" width="100%" height="90%"></iframe>
<input type="hidden" name="textfield" value="1" />
{% endif %}

</div>
<!-- /jhtmlarea -->

<!-- tabs -->
{% include "tt/tabs.tpl" %}
<!-- /tabs -->

<p style="margin-top: 30px"><input type="submit" name="submit" value="Изменить" /></p>

</form>

<div id="usersDialog" title="Выбор пользователей" style="text-align: left"></div>

<script type="text/javascript">
htmlarea();

{% for part in data.0.attach %}
$("#attach_files").append("<input type='hidden' name='attaches[]' value='{{ registry.rootPublic }}{{ registry.upload }}{{ part.filename }}' /><p><img border='0' src='{{ registry.uri }}img/paper-clip-small.png' alt='attach' style='position: relative; top: 4px; left: 1px' />{{ part.filename }}</p>");
{% endfor %}

{% for part in issRusers %}
$("#addedusers").append('{{ part.desc }}');
{% endfor %}

function flushAttaches() {
	$("#attach_files").html('');
};
</script>