<form method="post" action="/objects/add/">

{% for part in fields %}
{% if part.field %}
<p><b>{{ part.field }}</b></p>
<p><input type="text" name="{{ part.fid }}" /></p>
{% endif %}
{% endfor %}

<input type="hidden" name="tid" value="{{ fields.0.id }}" />

<input type="submit" name="submit" value="Добавить" />

</form>