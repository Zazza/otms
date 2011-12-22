<form method="post" action="{{ registry.uri }}profile/skin/?skin" name="setskin">

{% for skin in skins %}
<p>
	<input type="radio" id="{{ skin }}" name="skin" value="{{ skin }}" />
	<label for="{{ skin }}">{{ skin }}</label>
</p>
{% endfor %}

<p style="padding-top: 20px"><input type="submit" name="submit" value="Выбрать" /></p>

</form>