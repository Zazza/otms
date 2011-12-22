<div style="overflow: hidden; margin-bottom: 20px">

<span class="button" style="float: left; font-weight: bold">
<img src="{{ registry.uri }}img/edititem.gif" alt="" style="vertical-align: middle" />
<a style="outline: none; cursor: pointer; text-decoration: none" href="{{ registry.uri }}objects/edit/{{ data.0.id }}/">Правка</a>
</span>

<span class="button" style="margin-left: 10px; float: left; font-weight: bold">
<a style="text-decoration: none" href="{{ registry.uri }}objects/history/{{ data.0.id }}/" title="История">
<img src="{{ registry.uri }}img/clock-history.png" alt="История" style="vertical-align: middle" border="0" />
</a>
</span>

<span class="button" style="margin-left: 10px; float: left; font-weight: bold">
<a style="cursor: pointer" onclick="refreshurl('{{ siteName }}{{ registry.uri }}objects/{{ data.0.id }}/')">
<img src="{{ registry.uri }}img/enter.png" title="перейти к объекту" alt="object" border="0" style="vertical-align: middle" />
</a>
</span>

</div>

<div style="text-align: left">
{% if data.0.email %}
<p><b>email:</b> <a href="mailto: {{ data.0.email }}">{{ data.0.email }}</a></p>
{% endif %}

{% for part in data %}
{% if part.field %}
<p style="margin: 4px 0"><b>{{ part.field }}:</b>&nbsp;{{ part.val }}</p>
{% endif %}
{% endfor %}
</div>

<div style="text-align: left; margin-top: 20px; padding: 2px 4px; background-color: #EEE">Объект добавлен: <a style="cursor: pointer" onclick="getUserInfo('{{ data.0.auid }}')">{{ data.0.aname }} {{ data.0.asoname }}</a> <span style="color: #777">[{{ data.0.adate }}]</span></div>
{% if data.0.edate and  data.0.edate != data.0.adate %}
<div style="text-align: left; margin-top: 5px; padding: 2px 4px; background-color: #EEE">Последняя правка: <a style="cursor: pointer" onclick="getUserInfo('{{ data.0.euid }}')">{{ data.0.ename }} {{ data.0.esoname }}</a> <span style="color: #777">[{{ data.0.edate }}]</span></div>
{% endif %}