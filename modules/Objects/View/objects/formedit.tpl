<h3>Правка формы</h3>

<form method="post" accept="{{ registry.uri }}objects/forms/edit/?id={{ post.0.id }}/">

<p><b>Имя формы</b></p>
<p><input type="text" name="name" value="{{ post.0.name }}" /></p>

<p><b>Поля:</b></p>

<div style="height: 30px">
<div style="float: left; width: 200px; text-align: center; font-size: 11px">имя поля</div>
<div style="float: left; width: 80px; text-align: center; font-size: 11px">расширенное</div>
</div>

{% for part in post %}
{% if part.field %}

<div style="height: 30px">

<div style="float: left; width: 200px">
<input type="text" name="field[{{ part.fid }}]" value="{{ part.field }}" />
<input type="hidden" name="new[{{ part.fid }}]" value="0" />
</div>

<div style="float: left; width: 80px; text-align: center">
<input type="checkbox" name="expand[{{ part.fid }}]" {% if part.expand %}checked="checked"{% endif %} />
</div>

</div>

{% endif %}
{% endfor %}

<p style="margin-top: 10px"><img border="0" style="vertical-align: middle" alt="plus" src="{{ registry.uri }}img/plus-button.png" />&nbsp;<a style="cursor: pointer" onclick="addField()">Добавить новое поле</a></p>
<p style="margin-bottom: 10px">В созданном поле напишите его название, например: "Высота, м"</p>

<div id="field"></div>

<p style="margin-top: 10px"><input name="submit" type="submit" value="Правка" /></p>

</form>

<script type="text/javascript">
var i = 0;
function addField() {
    var val = '<div style="height: 30px">';
    val += '<div style="float: left; width: 200px"><input type="text" name="field[' + i + ']" /><input type="hidden" name="new[' + i + ']" value="1" /></div><div style="float: left; width: 80px; text-align: center"><input type="checkbox" name="expand[' + i + ']" /></div>';
    val += '</div>';
    $("#field").append(val);
    
    i++;
}
</script>