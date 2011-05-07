<span class="obj_list" style="margin-right: 7px">
<a href="{{ siteName }}{{ uri }}objects/{{ obj.0.id }}/" title="перейти к объекту"><img border="0" style="vertical-align: middle" alt="info" src="{{ uri }}img/enter.png" /></a>
</span>

<span style="margin-right: 7px">
<a style="cursor: pointer; margin-right: 2px" onclick="getInfo({{ obj.0.id }})"><img src="{{ uri }}img/information-button.png" title="полные данные" alt="info" border="0" style="vertical-align: middle" /></a>
</span>

{% if not ui.readonly %}
<span style="margin-right: 7px">
<a href="{{ siteName }}{{ uri }}tt/add/?oid={{ obj.0.id }}" title="добавить задачу"><img border="0" style="vertical-align: middle" alt="plus" src="{{ uri }}img/plus-button.png" /></a>
</span>
{% endif %}

<span style="float: right">
<label><input type="checkbox" name="obj[{{ obj.0.id }}]" style="position: relative; top: 3px" />выбрать</label>
</span>

<p>
{% for part in obj %}
<b>{{ part.field }}:</b>&nbsp;{{ part.val }}
{% endfor %}
</p>