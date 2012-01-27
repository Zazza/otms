<li><a href="#" class="dir">{{ key }}</a>
	<ul>
		{% for key, val in val %}
		<li><a href="{{ val }}">{{ key }}</a></li>
		{% endfor %}
	</ul>
</li>