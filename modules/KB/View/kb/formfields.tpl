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

<input type="submit" name="submit" value="Добавить" style="margin-top: 20px" />