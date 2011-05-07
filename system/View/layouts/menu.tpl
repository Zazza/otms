<div id="submenu">

<div id="middlemenu">
    <div id="topmenu">
        <a href="{{ registry.uri }}help/">Справка</a>
        {% if registry.auth %}<a href="{{ registry.uri }}exit/">Выход</a>{% endif %}
    </div>

    {% if registry.auth %}   
    <div style="float: right; padding-right: 50px; text-align: center"> 
        <div style="margin: 10px 0; font-weight: bold; font-size: 13px; color: #FFF">
        {{ now }}
        </div>
       
        <div style="margin-bottom: 5px">
            <p><img border="0" alt="" src="{{ registry.uri }}img/left/users.png" style="vertical-align: middle" />
            <b>{{ registry.ui.name }} {{ registry.ui.soname }}</b>
            
            {% if registry.ui.admin %}
            <span style="color: #800">(Администратор)</span>
            {% endif %}
            
            {% if registry.ui.readonly %}
            <span style="color: #800">(Только чтение)</span>
            {% endif %}
            </p>
        
            <p><b>Группа:</b>&nbsp;{{ registry.ui.gname }}</p>
        </div>
    </div>
    {% endif %}
    
    <div style="margin: 12px 0 0 30px"><img src="{{ registry.uri }}img/logo.png" alt="logo" border="0" /></div>
    
</div>


{% if categories %}
<div id="cat">

{% if registry.auth %}
<div id="subright">
<div id="find">
<form action="{{ registry.uri }}find/objects/" method="post" name="form_find" id="form_find">
<input type="text" name="find" id="in_find" />
<input name="button_find" id="but_find" type="submit" value="Найти" />
</form>
</div>
</div>
{% endif %}

{% for part in categories %}{% if part.selected %}<a href="{{ part.link }}" id="a_catmenu_sel"><div id="catmenu_sel">{{ part.name }}</div></a>{% else %}<a href="{{ part.link }}" class="a_catmenu"><div class="catmenu">{{ part.name }}</div></a>{% endif %}{% endfor %}
</div>
{% endif %}

</div>