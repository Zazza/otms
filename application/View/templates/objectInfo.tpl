<div style="text-align: left">
{% for part in data %}
{% if part.field %}
<p style="margin: 4px 0"><b>{{ part.field }}:</b>&nbsp;{{ part.val }}</p>
{% endif %}
{% endfor %}
</div>