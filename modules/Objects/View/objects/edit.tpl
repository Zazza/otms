<form method="post" action="{{ registry.uri }}objects/edit/{{ vals.0.id }}/">

<p style="margin: 10px 0"><a href="{{ registry.uri }}objects/{{ vals.0.id }}/">Перейти к объекту</a></p>

{% if email %}
<p style="margin: 10px 0">
<img border="0" src="{{ registry.uri }}img/plus-button.png" alt="" style="vertical-align: middle" />
<a onclick="addEmail('{{ email }}')" style="cursor: pointer; text-decoration: none">
<b>Добавить email:</b> {{ email }}</a>
</p>
{% else %}
<p style="margin: 10px 0">
<img border="0" src="{{ registry.uri }}img/plus-button.png" alt="" style="vertical-align: middle" />
<a onclick="addEmail('')" style="cursor: pointer; text-decoration: none">
<b>Добавить email</b></a>
</p>
{% endif %}

<div id="fieldemail"></div>

{% if vals.0.email %}
<p><b>Email</b></p>
<p><input type="text" name="email" value="{{ vals.0.email }}" /></p>
{% endif %}

{% for part in vals %}
{% if part.field %}
<p><b>{{ part.field }}</b></p>
{% if part.expand %}
<p><textarea name="{{ part.fid }}">{{ part.val }}</textarea></p>
{% else %}
<p><input type="text" name="{{ part.fid }}" value="{{ part.val|e }}" /></p>
{% endif %}
{% endif %}
{% endfor %}

<input type="hidden" name="tid" value="{{ vals.0.id }}" />

<input type="submit" name="submit" value="Изменить" style="margin-top: 20px" />

</form>

<script type="text/javascript">
function addEmail(email) {
	if ($("input[name='email']").height()) {
		$("input[name='email']").val(email);
	} else {
		$("#fieldemail").html('<p><b>Email</b></p><p><input type="text" name="email" value="' + email + '" /></p>');
	}
}
</script>