{% if not ui.readonly %}

<span>
<input type="checkbox" name="obj[{{ obj.0.id }}]" style="position: relative; top: 3px" />
</span>

<span>
<a href="{{ siteName }}{{ uri }}tt/add/?oid={{ obj.0.id }}" title="добавить задачу"><img border="0" style="vertical-align: middle" alt="plus" src="{{ uri }}img/plus-small.png" /></a>
</span>

<span>
<a style="cursor: pointer; margin-right: 2px" onclick="getInfo({{ obj.0.id }})"><img src="{{ uri }}img/information-small.png" title="полные данные" alt="info" border="0" style="vertical-align: middle" /></a>
</span>

<span>
<a href="{{ siteName }}{{ uri }}objects/{{ obj.0.id }}/" title="перейти к объекту" class="none">
{% for part in obj %}
{% if part.main %}
<b>{{ part.field }}:</b>&nbsp;{{ part.val }}
{% endif %}
{% endfor %}
</a>
</span>

{% endif %}