<div style="margin-bottom: 20px; overflow: hidden">

<div style="overflow: hidden">
    <div style="float: right">
		<span class="button" style="float: left; font-weight: bold; height: 17px">
		<img src="{{ registry.uri }}img/edititem.gif" alt="" style="vertical-align: middle" />
		{% if json %}
		<a style="outline: none; cursor: pointer; text-decoration: none" href="{{ registry.uri }}objects/setform/edit/?oaid={{ ai.id }}">Правка</a>
		{% else %}
		<a style="outline: none; cursor: pointer; text-decoration: none" href="{{ registry.uri }}objects/info/edit/?oaid={{ ai.id }}">Правка</a>
		{% endif %}
		</span>

		<span class="button" style="margin-left: 5px; float: left; font-weight: bold; height: 17px">
		<img src="{{ registry.uri }}img/delete.png" alt="" style="vertical-align: middle" />
		<a style="cursor: pointer; text-decoration: none" onclick="delAdvConfirm({{ ai.id }})" title="удалить">Удалить</a>
		</span>
		
		<span class="button" style="margin-left: 5px; float: left; font-weight: bold; height: 17px">
		<a style="text-decoration: none" href="{{ registry.uri }}kb/history/{{ ai.id }}/" title="История">
		<img src="{{ registry.uri }}img/clock-history.png" alt="История" style="vertical-align: middle" />
		</a>
		</span>
    </div>
</div>

{% if ai.param %}
<div class="info" style="float: left">{{ ai.param }}</div>
{% endif %}
    
<p><b>Название:</b> {{ ai.title }}</p>
<p>{{ ai.val }}</p>



<div style="text-align: left; margin-top: 20px; padding: 2px 4px; background-color: #EEE">Объект добавлен: <a style="cursor: pointer" onclick="getUserInfo('{{ ai.auid }}')">{{ ai.aname }} {{ ai.asoname }}</a> <span style="color: #777">[{{ ai.adate }}]</span></div>
{% if ai.edate != '0000-00-00 00:00:00' %}
<div style="text-align: left; margin-top: 5px; padding: 2px 4px; background-color: #EEE">Последняя правка: <a style="cursor: pointer" onclick="getUserInfo('{{ ai.euid }}')">{{ ai.ename }} {{ ai.esoname }}</a> <span style="color: #777">[{{ ai.edate }}]</span></div>
{% endif %}
</div>