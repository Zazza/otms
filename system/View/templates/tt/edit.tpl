<form method="post" action="{{ uri }}tt/edit/">

<input type="hidden" name="tid" value="{{ data.0.id }}" />

<h2 style="margin: 10px 0 20px 0"><b>Правка задачи</b></h2>



<div style="border: 1px solid #AAA; padding: 10px; margin-bottom: 10px">

<p style="margin-bottom: 10px">
<b>1. Объект</b>

{% if obj %}
<div class="info" style="margin-bottom: 20px">
{% for part in obj %}
<p><b>{{ part.field }}:</b>&nbsp;{{ part.val }}</p>
{% endfor %}
</div>
{% endif %}

</div>



<div style="border: 1px solid #AAA; padding: 10px; margin-bottom: 10px">

<p style="margin-bottom: 10px">
<b>2. Текст задачи</b>
<span class="sel" style="display: inline; cursor: pointer; width: 100px; margin-left: 50px; padding: 4px 7px" onclick="showTaskWindow()"><img src="{{ uri }}img/plus-button.png" style="vertical-align: middle" alt="" border="0" /> Текст задачи</span>
<span style="margin-left: 10px"><label><input type="checkbox" name="secure" style="vertical-align: middle" {% if data.0.secure %} checked {% endif %} /> доступ только для адресованных</label></span>
</p>

<div style="margin-top: 10px" id="tasktext" class="obj">{{ data.0.text }}</div>
<textarea name="task" id="task" style="margin-top: 10px; display: none">{{ data.0.text }}</textarea>

</div>



<div style="border: 1px solid #AAA; padding: 10px; margin-bottom: 10px; overflow: hidden">

<div style="float: left; width: 300px">
<p style="margin-bottom: 10px"><b>3. Назначение ответственных</b></p>

<p style="margin-bottom: 5px">
    <select id="ruser">
    {% for part in rusers %}
    
    {% if part.type == "all" %}
        {% set string = 'все пользователи' %}
        {% set style = 'style="color: red; margin: 5px 0"' %}
    {% endif %}
    
    {% if part.type == "g" %}
        {% set string = part.desc %}
        {% set style = 'style="color: green; margin: 5px 0"' %}
    {% endif %}
    
    {% if part.type == "u" %}
        {% set string = part.desc %}
        {% set style = 'style="margin: 5px 0 5px 10px"' %}
    {% endif %}
    
    <option {{ style }} value="{{ part.id }}" title="{{ part.type }}">{{ string }}</option>
    
    {% endfor %}
    </select>
</p>

<p style="margin-top: 10px"><img border="0" style="vertical-align: middle;" alt="plus" src="{{ uri }}img/plus-button.png" /> <a style="cursor: pointer" onclick="addruser()">добавить ответственного</a></p>
</div>

<div style="margin-left: 320px">
    <p style="margin: 10px 0"><b>Назначены (<a style="cursor: pointer" onclick="delRusers()">очистить</a>):</b></p>
    <p id="addedusers" style="margin: 10px 0"></p>
</div>

</div>



<div style="border: 1px solid #AAA; padding: 10px; margin-bottom: 10px">

<p style="margin-bottom: 10px"><b>4. Важность</b></p>

<select name="imp">
    <option value="1" {% if data.0.imp == 1 %}selected="selected"{% endif %}>1</option>
    <option value="2" {% if data.0.imp == 2 %}selected="selected"{% endif %}>2</option>
    <option value="3" {% if data.0.imp == 3 %}selected="selected"{% endif %}>3</option>
    <option value="4" {% if data.0.imp == 4 %}selected="selected"{% endif %}>4</option>
    <option value="5" {% if data.0.imp == 5 %}selected="selected"{% endif %}>5</option>
</select>

</div>



<div style="border: 1px solid #AAA; padding: 10px; margin-bottom: 10px; overflow: hidden">

<div style="float: left; width: 300px">
<p style="margin-bottom: 10px"><b>5. Сроки</b></p>

<p><b>Переодичность</b></p>
<select id="type" name="type" onchange="changeType()">
    <option value="0" {% if data.0.type == 0 %}selected="selected"{% endif %}>Без условий</option>
    <option value="1" {% if data.0.type == 1 %}selected="selected"{% endif %}>Один раз</option>
    <option value="2" {% if data.0.type == 2 %}selected="selected"{% endif %}>Повторять</option>
</select>
</div>

<div style="margin-left: 320px" id="advDeadline">
    <div id="global">
        <p><b>Начиная с:</b></p>
        <p>
            <input type="text" name="startdate_global" class="startdate" value="{{ data.0.startdate }}" />
            <input type="text" name="starttime_global" class="starttime" value="{{ data.0.starttime }}" />
        </p>
    </div>

    <div id="noiter" style="display: none">
        <p><b>Начиная с:</b></p>
        <p>
            <input type="text" name="startdate_noiter" class="startdate" value="{{ data.0.startdate }}" />
            <input type="text" name="starttime_noiter" class="starttime" value="{{ data.0.starttime }}" />
        </p>
        
        <p><b>продолжительность:</b></p>
        <p>
            <input type="text" name="lifetime_noiter" value="{{ data.0.deadline }}" />
            <select name="timetype_noiter">
                <option value="min" {% if data.0.deadline_date == "минут" %}selected="selected"{% endif %}>минут</option>
                <option value="hour" {% if data.0.deadline_date == "часов" %}selected="selected"{% endif %}>часов</option>
                <option value="day" {% if data.0.deadline_date == "дней" %}selected="selected"{% endif %}>дней</option>
            </select>
        </p>
    </div>
    
    <div id="iter" style="display: none">
        <p><b>Повторять каждые:</b></p>
        <p>
            <input type="text" name="itertime" value="{{ data.0.iteration }}" />
            <select name="timetype_itertime">
                <option value="day" {% if data.0.timetype_iteration == "day" %}selected="selected"{% endif %}>дней</option>
                <option value="month" {% if data.0.timetype_iteration == "month" %}selected="selected"{% endif %}>месяцев</option>
            </select>
        </p>
        
        <p><b>Начиная с:</b></p>
        <p>
            <input type="text" name="startdate_iter" class="startdate" value="{{ data.0.startdate }}" />
            <input type="text" name="starttime_iter" class="starttime" value="{{ data.0.starttime }}" />
        </p>
        
        <p><b>продолжительность:</b></p>
        <p>
            <input type="text" name="lifetime_iter" value="{{ data.0.deadline }}" />
            <select name="timetype_iter">
                <option value="min" {% if data.0.deadline_date == "минут" %}selected="selected"{% endif %}>минут</option>
                <option value="hour" {% if data.0.deadline_date == "часов" %}selected="selected"{% endif %}>часов</option>
                <option value="day" {% if data.0.deadline_date == "дней" %}selected="selected"{% endif %}>дней</option>
                <option value="0" {% if data.0.deadline_date == "0" %}selected="selected"{% endif %}>неограничено</option>
            </select>
        </p>
    </div>
</div>

</div>

<p style="margin-top: 30px"><input type="submit" name="submit" value="Изменить" /></p>

</form>

<script type="text/javascript">
{% for part in issRusers %}
$("#addedusers").append('{{ part.desc }}');
{% endfor %}

$(".startdate").datepicker({ dateFormat: 'yy-mm-dd' });

function addruser() {
    var data = "action=getUser&id=" + $("#ruser").val() + "&type=" + $("#ruser").find("option:selected").attr("title");
	$.ajax({
		type: "POST",
		url: "{{ uri }}ajax/users/",
		data: data,
		success: function(res) {
            $("#addedusers").append(res);
		}
	})
}

function delRusers() {
    $("#addedusers").text("");
}

changeType();

function changeType() {
    var type = $("#type").val();
    
    if (type == "0") {
        $("#global").show();
        $("#noiter").hide();
        $("#iter").hide();
    } else if (type == "1") {
        $("#global").hide();
        $("#noiter").show();
        $("#iter").hide();
    } else {
        $("#global").hide();
        $("#noiter").hide();
        $("#iter").show()
    }
}
</script>