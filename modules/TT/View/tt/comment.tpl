{% if comment.sendmail %}
<div style="overflow: hidden; margin-bottom: 30px; background-color: #E0FFE7; margin-left: 40px; padding: 4px">
{% else %}
<div id="d{{ comment.id }}" style="{% if new %}background-color: #E0FFE7; {% endif %}overflow: hidden; margin-bottom: 30px; margin-left: 40px; padding: 4px">
{% endif %}

{% if not comment.mail_id %}
<div style="float: left; text-align: center; margin-right: 10px">
	<img class="avatar" id="ava" src="{{ comment.ui.avatar }}" alt="аватар" />
	<br />
	{% if not comment.remote %}
	{% if comment.ui.status %}
	<div style="font-size: 10px; color: green">[online]</div>
	{% else %}
	<div style="font-size: 10px; color: red">[offline]</div>
	{% endif %}
	{% endif %}

	{% if comment.sendmail %}
	<p style="text-align: center">
	<img id="smail{{ comment.id }}" alt="" src="{{ registry.uri }}img/mail--exclamation.png" border="0" />
	</p>
	{% elseif data.0.mail_id != 0 %}
	{% if comment.ui.uid == registry.ui.id %}
	<p style="text-align: center">
	<a id="shref{{ comment.id }}" style="cursor: pointer" onclick="sendMailCommentConfirm('{{ data.0.text.0.email }}', '{{ comment.id }}')" title="отправить сообщение">
	<img id="smail{{ comment.id }}" alt="" src="{{ registry.uri }}img/left/mail-send.png" border="0" />
	</a>
	</p>
	{% endif %}
	{% endif %}
</div>
{% else %}
<div style="float: left; margin-right: 10px">
	<img class="avatar" id="ava" src="{{ registry.uri }}img/noavatar.gif" alt="аватар" />
</div>
{% endif %}

<div style="margin-left: 70px">
	{% if not comment.mail_id %}
		{% if comment.remote %}
			<p style="font-weight: bold">{{ comment.ui.name }} {{ comment.ui.soname }} (группа {{ comment.ui.gname }})</p>
		{% else %}
			{% if comment.object %}
			<span style="font-weight: bold">[API]</span>
			{% endif %}
			<a style="font-weight: bold; cursor: pointer" onclick="getUserInfo('{{ comment.uid }}')">{{ comment.ui.name }} {{ comment.ui.soname }}</a>
		{% endif %}
		<span style="color: #999">{{ comment.timestamp }}</span>
	{% else %}
		{% if comment.text.0.personal %}{{ comment.text.0.personal }}&nbsp;{% endif %}
		<a href="mailto: {{ comment.text.0.email }}" style="margin-right: 10px">{{ comment.text.0.email }}</a>
		<div style="color: #999">
		{% if comment.text.0.date != "0000-00-00 00:00:00" %}
		{{ comment.text.0.date }}
		{% else %}
		{{ comment.text.0.timestamp }}
		{% endif %}
		</div>
	{% endif %}

	{% if comment.attaches %}
	<div class="sel" style="background-color: #EEF">
	{% for part in comment.attaches %}
	{% if comment.remote %}
	<a class="attach" style="margin-right: 10px; cursor: pointer" href="{{ registry.uri }}tt/attach/?remote=1&tdid={{ comment.id }}&filename={{ part.filename }}">{{ part.filename }}</a>
	{% else %}
	<a class="attach" style="margin-right: 10px; cursor: pointer" href="{{ registry.uri }}tt/attach/?tdid={{ comment.id }}&filename={{ part.filename }}">{{ part.filename }}</a>
	{% endif %}
	{% endfor %}
	</div>
	{% endif %}

	{% if comment.status_id != 0 %} <p style="margin: 8px 0"><span style="padding: 2px 4px" class="info"><b>Статус:</b> {{ comment.status }}</span></p> {% endif %}
	
	{% if comment.mail_id == 0 %}
	<p>{{ comment.text }}</p>
	{% else %}
	<p>{% include "tt/mail.tpl" with {'mail': comment.text, 'task': 1} %}</p>
	{% endif %}
</div>
</div>