<form method="post" action="{{ uri }}objects/add/">

{% for part in fields %}
{% if part.field %}
<p><b>{{ part.field }}</b></p>
{% if part.expand %}
<p><textarea name="{{ part.fid }}"></textarea></p>
{% else %}
<p><input type="text" name="{{ part.fid }}" /></p>
{% endif %}
{% endif %}
{% endfor %}

<input type="hidden" name="tid" value="{{ fields.0.id }}" />
<input type="hidden" name="ttypeid" value="{{ fields.0.ttypeid }}" />

<input type="submit" name="submit" value="Добавить" />

</form>