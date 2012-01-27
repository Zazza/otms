{% include "find/tt/taskinfo.tpl" %}

{% if data.0.close == "1" %}
<div class="obj" style="background-color: #F7F7F7; border-bottom: 1px solid #EEE; border-left: 1px solid #EEE; border-right: 1px solid #EEE">
{% elseif data.0.secure == "1" %}
<div class="obj" style="background-color: #F0FFF0; border-bottom: 1px solid #EEE; border-left: 1px solid #EEE; border-right: 1px solid #EEE">
{% elseif data.0.type == "1" and data.0.expire %}
<div class="obj" style="background-color: #FFFDF0; border-bottom: 1px solid #EEE; border-left: 1px solid #EEE; border-right: 1px solid #EEE">
{% else %}
<div class="obj" style="border-bottom: 1px solid #EEE; border-left: 1px solid #EEE; border-right: 1px solid #EEE">
{% endif %}

<div style="border-bottom: 1px solid #EEE; height: 30px; margin-bottom: 10px">

<div style="float: right">
{% if data.0.close == 1 %}<div class="taskstatus">[завершено]</div>{% endif %}
{% if data.0.secure == 1 %}<div class="taskstatus">[приватная]</div>{% endif %}
{% if data.0.type == "1" and data.0.expire %}<div class="taskstatus">[просроченная]</div>{% endif %}
</div>


<div style="float: left; margin-left: -10px">

<ul class="dropdown taskbutton">
	<li><a href="#" class="dir">Ответственные</a>
		<ul>
			<div class="taskadv">{% include "find/tt/info/responsible.tpl" %}</div>
		</ul>
	</li>
	<li><a href="#" class="dir">Сроки</a>
		<ul>
			<div class="taskadv">{% include "find/tt/info/period.tpl" %}</div>
		</ul>
	</li>
</ul>

</div>


</div>

<div style="margin-bottom: 10px; padding: 2px 0; font-family: Arial; font-size: 14px; font-weight: bold; overflow: hidden; clear: both">


<div style="float: right">


<div style="text-align: right">
{% if type != "draft" %}
<a class="title" style="color: #048" href="{{ registry.uri }}tt/{{ data.0.id }}/">№{{ data.0.id }}</a>
{% else %}
<a class="title" style="color: #048" href="{{ registry.uri }}tt/draft/{{ data.0.id }}/">№{{ data.0.id }}</a>
{% endif %}
</div>

<div style="text-align: right; font-size: 12px; padding-bottom: 3px; color: green">
{{ data.0.group }}
</div>

<!-- приоритет -->
<div style="float: right; font-size: 12px; font-weight: normal">
{% if data.0.imp == 1 %}
<div style="height: 14px; width: 20px; border: 2px solid #00ffcc; text-align: center"><span style="position:relative; bottom: 2px">1/5</span></div>
{% elseif data.0.imp == 2 %}
<div style="height: 14px; width: 40px; border: 2px solid #00ffcc; text-align: center"><span style="position:relative; bottom: 2px">2/5</span></div>
{% elseif data.0.imp == 3 %}
<div style="height: 14px; width: 60px; border: 2px solid #ffcc00; text-align: center"><span style="position:relative; bottom: 2px">3/5</span></div>
{% elseif data.0.imp == 4 %}
<div style="height: 14px; width: 80px; border: 2px solid #ff0000; text-align: center"><span style="position:relative; bottom: 2px">4/5</span></div>
{% elseif data.0.imp == 5 %}
<div style="height: 14px; width: 100px; border: 2px solid #ff0000; text-align: center"><span style="position:relative; bottom: 2px">5/5</span></div>
{% endif %}
</div>
<!-- END приоритет -->

</div>

{% if data.0.mail_id == 0 %}
<span style="overflow: hidden">
<div style="float: left; text-align: center; margin-right: 10px">
	<img class="avatar" id="ava" src="{{ author.avatar }}" alt="аватар" />
	<br />
	{% if data.0.remote_id == 0 %}
	{% if author.status %}
	<div style="font-size: 10px; color: green">[online]</div>
	{% else %}
	<div style="font-size: 10px; color: red">[offline]</div>
	{% endif %}
	{% endif %}
</div>
<div style="margin: 5px 0 0 65px">
	<div style="margin-bottom: 5px">
	{% if data.0.remote_id != 0 %}
	{{ author.soname }} {{ author.name }} (группа {{ author.gname }})
	{% else %}
	<a style="cursor: pointer" onclick="getUserInfo('{{ author.id }}')">{{ author.soname }} {{ author.name }}</a>
	{% endif %}
	</div>
	<div style="color: #AAA">{{ data.0.startF }}</div>
</div>
</span>
{% else %}
<span style="overflow: hidden">
<div style="float: left; margin-right: 10px">
	<img class="avatar" id="ava" src="{{ registry.uri }}img/noavatar.gif" alt="аватар" />
	<br />
	{% if author.status %}
	<div>[online]</div>
	{% else %}
	<div>[offline]</div>
	{% endif %}
</div>
<div style="margin: 5px 0 0 65px">
	<div style="margin-bottom: 10px">
	{% if data.0.text.0.personal %}({{ data.0.text.0.personal }})&nbsp;{% endif %}
	<a href="mailto: {{ data.0.text.0.email }}" style="margin-right: 10px">{{ data.0.text.0.email }}</a>
	</div>
	<div style="color: #AAA">{{ data.0.startF }}</div>
</div>
</span>
{% endif %}

</div>


{% if data.0.attach %}
<div class="sel" style="float: left; background-color: #EEF; margin-left: 70px">
{% for part in data.0.attach %}
{% if type != "draft" %}
{% if data.0.remote_id != 0 %}
<a class="attach" style="margin-right: 10px; cursor: pointer" href="{{ registry.uri }}tt/attach/?remote=1&tid={{ data.0.id }}&filename={{ part.filename }}">{{ part.filename }}</a>
{% else %}
<a class="attach" style="margin-right: 10px; cursor: pointer" href="{{ registry.uri }}tt/attach/?tid={{ data.0.id }}&filename={{ part.filename }}">{{ part.filename }}</a>
{% endif %}
{% else %}
<a class="attach" style="margin-right: 10px; cursor: pointer" href="{{ registry.uri }}tt/attach/?did={{ data.0.id }}&filename={{ part.filename }}">{{ part.filename }}</a>
{% endif %}
{% endfor %}
</div>
{% endif %}

{% if data.0.mail_id != 0 %}
<div class="sel" style="clear: both; font-size: 12px; margin-left: 70px">
{% include "tt/mail.tpl" with {'mail': data.0.text, 'task': 1} %}
</div>
{% else %}
<div style="padding: 10px 0; clear: both; font-size: 12px; margin-left: 70px">{{ data.0.text }}</div>
{% endif %}

<!-- кнопки-внизу -->
{% if type != "draft" %}

<p style="margin-top: 20px">

<span style="float: right; font-size: 12px">
<a href="{{ registry.uri }}tt/{{ data.0.id }}/" style="text-decoration: none">
<img src="{{ registry.uri }}img/user-medium.png" alt="" style="position: relative; top: 3px" />
{{ numComments }} {% if newComments > 0 %}<span style="color: green; font-weight: bold">+{{ newComments }}</span>{% endif %}
</a>
</span>

<span class="button" style="float: left; font-weight: bold; height: 17px">
<a style="text-decoration: none" href="{{ registry.uri }}tt/history/{{ data.0.id }}/" title="История">
<img src="{{ registry.uri }}img/clock-history.png" alt="История" style="vertical-align: middle" border="0" />
</a>
</span>

{% if data.0.close == 0%}
{% if data.0.spam != 0 %}
<span class="button" style="margin-left: 10px; float: left; font-weight: bold">
<a style="cursor: pointer; text-decoration: none" onclick="spam({{ data.0.id }})" title="отписаться от рассылки по задаче">
<img src="{{ registry.uri }}img/mail--minus.png" alt="" style="vertical-align: middle" />
</a>
</span>
{% else %}
<span class="button" style="margin-left: 10px; float: left; font-weight: bold">
<a style="cursor: pointer; text-decoration: none" onclick="spam({{ data.0.id }})" title="подписаться на рассылку по задаче">
<img src="{{ registry.uri }}img/mail--plus.png" alt="" style="vertical-align: middle" />
</a>
</span>
{% endif %}
{% endif %}

{% if registry.ui.id == data.0.who %}
{% if data.0.close == 0 %}
<span class="button" style="margin-left: 10px; float: left; font-weight: bold">
<a style="cursor: pointer; text-decoration: none" href="{{ registry.uri }}tt/edit/{{ data.0.id }}/">
<img src="{{ registry.uri }}img/edititem.gif" alt="" style="vertical-align: middle" />
Правка
</a>
</span>

{% if registry.ui.id == data.0.who %}
<span class="button" style="margin-left: 10px; float: left; font-weight: bold">
<a style="cursor: pointer; text-decoration: none" onclick="closeTask({{ data.0.id }})">
<img src="{{ registry.uri }}img/inbox-download.png" alt="" style="vertical-align: middle" />
Закрыть
</a>
</span>
{% endif %}

{% endif %}
{% endif %}

</p>
{% else %}
<!-- Если черновик -->
<p style="margin-top: 20px">

<span class="button" style="float: left; font-weight: bold">
<a style="cursor: pointer; text-decoration: none" href="{{ registry.uri }}tt/draftedit/{{ data.0.id }}/">
<img src="{{ registry.uri }}img/edititem.gif" alt="" style="vertical-align: middle" />
Правка
</a>
</span>

<span class="button" style="margin-left: 10px; float: left; font-weight: bold">
<a style="cursor: pointer; text-decoration: none" onclick="delDraftConfirm({{ data.0.id }})">
<img src="{{ registry.uri }}img/inbox-download.png" alt="" style="vertical-align: middle" />
Удалить
</a>
</span>

</p>
{% endif %}
<!-- END кнопки-внизу -->

</div>