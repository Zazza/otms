<form method="post" action="/objects/edit/{{ vals.0.id }}/">

<p style="margin: 10px 0"><a href="/objects/{{ vals.0.id }}/">Перейти к объекту</a></p>

{% for part in vals %}
{% if part.field %}
<p><b>{{ part.field }}</b></p>
{% if part.expand %}
<p><textarea name="{{ part.fid }}">{{ part.val }}</textarea></p>
{% else %}
<p><input type="text" name="{{ part.fid }}" value="{{ part.val }}" /></p>
{% endif %}
{% endif %}
{% endfor %}

<input type="hidden" name="tid" value="{{ vals.0.id }}" />

<input type="submit" name="submit" value="Изменить" />

</form>