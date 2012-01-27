<h3>Форма</h3>

<form method="post" action="{{ registry.uri }}objects/setform/edit/?oaid={{ oaid }}">

<div id="fields">
{% for key,val in afinfo %}
<p><b>{{ key }}</b>:</p>
<p><textarea name="{{ key }}">{{ val }}</textarea></p>
{% endfor %}
</div>

<input type="submit" name="submit" value="Изменить" />

</form>