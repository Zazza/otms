<div id="submenu">

<div id="subright">
<div id="find">
<form action="/find/" method="post" name="form_find" id="form_find">
<input type="text" name="find" id="in_find" />
<input name="button_find" id="but_find" type="submit" value="Найти" />
</form>
</div>
</div>

{% if categories %}
<div id="cat">

<div style="float: left; margin-right: 10px">&nbsp;</div>

{% for part in categories %}{% if part.selected %}<a href="{{ part.link }}" id="a_catmenu_sel"><div id="catmenu_sel">{{ part.name }}</div></a>{% else %}<a href="{{ part.link }}" class="a_catmenu"><div class="catmenu">{{ part.name }}</div></a>{% endif %}{% endfor %}
</div>
{% endif %}

<div id="menucover">&nbsp;</div>


</div>