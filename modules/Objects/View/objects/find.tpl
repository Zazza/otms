<form action="{{ registry.uri }}objects/" method="post" name="form_list_obj">

<div id="objl">
<div id="ajax-load"><img src="{{ registry.uri }}img/ajax-loader.gif" alt="ajax-loader.gif" border="0" /></div>
<ul id="structure" class="filetree" style="display: none">{{ list }}</ul>
</div>

<input name="move_confirm" id="move_confirm" type="hidden" value="no" />
<input name="tName" id="tName" type="hidden" value="" />
<input name="tTypeName" id="tTypeName" type="hidden" value="" />

<div id="moveDialog" title="Перемещение" style="display: none">
    <p>Действительно переместить объекты?</p>
    
    <select name="template" id="template">
        {% for template in templates %}
        <option value="{{ template.id }}">{{ template.name }}</option>
        {% endfor %}
    </select>
    
    {% for template in templates %}
    
    <select name="tType" class="diftree" id="tree_{{ template.id }}" onclick="setTpl()" style="display: none">
        {% for part in tree[template.id] %}
        <option value="{{ part.id }}">{{ part.name }}</option>
        {% endfor %}
    </select>
    
    {% endfor %}
</div>

</form>