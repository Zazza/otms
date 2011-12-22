<hr />

<p style="font-weight: bold; color: #999">Сортировать задачи:</p>


<p class="lmenu {% if sort.sort == "group" %}sellmenu{% endif %}">
<img id="s_group" src="{{ registry.uri }}img/tall-down-arrow.gif" alt="" style="cursor: pointer; position: relative; top: 2px" />
<a class="aleft" style="cursor: pointer" onclick="setSort('group', false)">По группе</a>
</p>
<div style="display: none" id="sp_group" class="sortGroups">
<p style="margin-left: 10px"><a style="cursor: pointer" onclick="setSort('group', '0')" class="{% if sort.id == "0" %}selsubmenu{% endif %}">Без группы</a></p>
{% for key, part in sg.gname %}
<p style="margin-left: 10px"><a style="cursor: pointer" onclick="setSort('group', {{ key }})" class="{% if sort.id == key %}selsubmenu{% endif %}">{{ part }}</a></p>
{% endfor %}
</div>

<p style="margin-top: 5px" class="lmenu {% if sort.sort == "obj" %}sellmenu{% endif %}">
<img id="s_obj" src="{{ registry.uri }}img/tall-down-arrow.gif" alt="" style="cursor: pointer; position: relative; top: 2px" />
<a class="aleft" style="cursor: pointer" onclick="setSort('obj', false)">По объектам</a>
</p>
<div style="display: none" id="sp_obj" class="sortGroups">
{% for key, obj in sg.obj %}
{% if obj %}
<div class="info" style="margin-top: 5px; padding: 0">
{% for part in obj %}
<p class="{% if sort.id == key %}selsubmenu{% endif %}" style="cursor: pointer; margin-left: 10px" onclick="setSort('obj', {{ key }})"><b>{{ part.field }}:</b>&nbsp;{{ part.val }}</p>
{% endfor %}
</div>
{% endif %}
{% endfor %}
</div>

<p style="margin-top: 5px" class="lmenu {% if sort.sort == "imp" %}sellmenu{% endif %}">
<img id="s_imp" src="{{ registry.uri }}img/tall-down-arrow.gif" alt="" style="cursor: pointer; position: relative; top: 2px" />
<a class="aleft" style="cursor: pointer" onclick="setSort('imp', false)">По приоритету</a>
</p>
<div style="display: none" id="sp_imp" class="sortGroups">
{% for part in sg.imp %}
<p style="margin-left: 10px"><a style="cursor: pointer" onclick="setSort('imp', {{ part }})" class="{% if sort.id == part %}selsubmenu{% endif %}">Приоритет: {{ part }}</a></p>
{% endfor %}
</div>

<p style="margin-top: 5px" class="lmenu {% if sort.sort == "type" %}sellmenu{% endif %}">
<img id="s_type" src="{{ registry.uri }}img/tall-down-arrow.gif" alt="" style="cursor: pointer; position: relative; top: 2px" />
<a class="aleft" style="cursor: pointer" onclick="setSort('type', false)">По типу</a>
</p>
<div style="display: none" id="sp_type" class="sortGroups">
{% for part in sg.type %}
{% if part == "0" %}<p style="margin-left: 10px"><a style="cursor: pointer" onclick="setSort('type', 0)" class="{% if sort.id == part %}selsubmenu{% endif %}">Глобальные</a></p>{% endif %}
{% if part == "1" %}<p style="margin-left: 10px"><a style="cursor: pointer" onclick="setSort('type', 1)" class="{% if sort.id == part %}selsubmenu{% endif %}">Ограниченные по времени</a></p>{% endif %}
{% if part == "2" %}<p style="margin-left: 10px"><a style="cursor: pointer" onclick="setSort('type', 2)" class="{% if sort.id == part %}selsubmenu{% endif %}">Повторяющиеся</a></p>{% endif %}
{% endfor %}
</div>

<p style="margin-top: 5px" class="lmenu {% if sort.sort == "date" %}sellmenu{% endif %}">
<a class="aleft" style="cursor: pointer; margin-left: 13px" onclick="setSort('date', false)">По дате</a>
</p>


<script type="text/javascript">
function setSort(type, id) {
	var data = "action=setSortMyTt&sort=" + type + "&id=" + id;

	$.ajax({
	        type: "POST",
	        url: "{{ registry.uri }}ajax/tt/",
	        data: data,
	        success: function(res) {
				document.location.href = document.location.href;
	        }
	});
}

$("#sp_{{ sort.sort }}").show();

$("#s_group").click(function(){
    $(".sortGroups").slideUp();
    $("#sp_group").show();
})
$("#s_imp").click(function(){
    $(".sortGroups").slideUp();
    $("#sp_imp").show();
})
$("#s_obj").click(function(){
    $(".sortGroups").slideUp();
    $("#sp_obj").show();
})
$("#s_type").click(function(){
    $(".sortGroups").slideUp();
    $("#sp_type").show();
})
</script>