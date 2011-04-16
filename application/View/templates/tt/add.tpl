<form method="post" action="/tt/add/?oid={{ oid }}&date={{ now_date }}">

<h2 style="margin: 10px 0 20px 0"><b>Новая задача</b></h2>



<div style="border: 1px solid #AAA; padding: 10px; margin-bottom: 10px">

<p style="margin-bottom: 10px">
<b>1. Объект</b>

{% if obj %}
<div class="info" style="margin-bottom: 20px">
{% for part in obj %}
<p><b>{{ part.field }}:</b>&nbsp;{{ part.val }}</p>
{% endfor %}
</div>
{% else %}
<p style="margin-bottom: 10px"><a style="cursor: pointer" onclick="selObject()">Выбрать объект</a></p>
<div class="info" id="newObj" style="margin-bottom: 20px; display: none">
<div id="selObj" style="margin-bottom: 10px"></div>
<input type="hidden" id="selObjHid" name="selObjHid" />
</div>
{% endif %}

</div>



<div style="border: 1px solid #AAA; padding: 10px; margin-bottom: 10px">

<p style="margin-bottom: 10px">
<b>2. Текст задачи</b>
<a class="sel" style="display: inline; cursor: pointer; width: 100px; margin-left: 50px; padding: 4px 7px" onclick="showTaskWindow()"><img src="/img/plus-button.png" style="vertical-align: middle" alt="" border="0" /> Текст задачи</a>
<span style="margin-left: 10px"><label><input type="checkbox" name="secure" style="vertical-align: middle" /> доступ только для адресованных</label></span>
</p>

<div style="margin-top: 10px; display: none" id="tasktext" class="obj"></div>
<textarea name="task" id="task" style="margin-top: 10px; display: none"></textarea>

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

<p style="margin-top: 10px"><img border="0" style="vertical-align: middle;" alt="plus" src="/img/plus-button.png" /> <a style="cursor: pointer" onclick="addruser()">добавить ответственного</a></p>
</div>

<div style="margin-left: 320px">
    <p style="margin: 10px 0"><b>Назначены (<a style="cursor: pointer" onclick="delRusers()">очистить</a>):</b></p>
    <p id="addedusers" style="margin: 10px 0"></p>
</div>

</div>



<div style="border: 1px solid #AAA; padding: 10px; margin-bottom: 10px">

<p style="margin-bottom: 10px"><b>4. Важность</b></p>

<select name="imp">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3" selected="selected">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
</select>

</div>



<div style="border: 1px solid #AAA; padding: 10px; margin-bottom: 10px; overflow: hidden">

<div style="float: left; width: 300px">
<p style="margin-bottom: 10px"><b>5. Сроки</b></p>

<p><b>Переодичность</b></p>
<select id="type" name="type">
    <option value="0">Без условий</option>
    <option value="1">Один раз</option>
    <option value="2">Повторять</option>
</select>
</div>

<div style="margin-left: 320px" id="advDeadline">
    <div id="global">
        <p><b>Начиная с:</b></p>
        <p>
            <input type="text" name="startdate_global" class="startdate" value="{{ now_date }}" />
            <input type="text" name="starttime_global" class="starttime" value="{{ now_time }}" />
        </p>
    </div>

    <div id="noiter" style="display: none">
        <p><b>Начиная с:</b></p>
        <p>
            <input type="text" name="startdate_noiter" class="startdate" value="{{ now_date }}" />
            <input type="text" name="starttime_noiter" class="starttime" value="{{ now_time }}" />
        </p>
        
        <p><b>продолжительность:</b></p>
        <p>
            <input type="text" name="lifetime_noiter" />
            <select name="timetype_noiter">
                <option value="min">минут</option>
                <option value="hour">часов</option>
                <option value="day" selected="selected">дней</option>
            </select>
        </p>
    </div>
    
    <div id="iter" style="display: none">
        <p><b>Повторять каждые:</b></p>
        <p>
            <input type="text" name="itertime" value="1" />
            &nbsp;<b>дней</b>
        </p>
        
        <p><b>Начиная с:</b></p>
        <p>
            <input type="text" name="startdate_iter" class="startdate" value="{{ now_date }}" />
            <input type="text" name="starttime_iter" class="starttime" value="{{ now_time }}" />
        </p>
        
        <p><b>продолжительность:</b></p>
        <p>
            <input type="text" name="lifetime_iter" />
            <select name="timetype_iter">
                <option value="min">минут</option>
                <option value="hour">часов</option>
                <option value="day">дней</option>
                <option value="0" selected="selected">неограничено</option>
            </select>
        </p>
    </div>
</div>

</div>

<p style="margin-top: 30px"><input type="submit" name="submit" value="Создать" /></p>

</form>

<script type="text/javascript">
$(".startdate").datepicker({ dateFormat: 'yy-mm-dd' });

function addruser() {
    var data = "action=getUser&id=" + $("#ruser").val() + "&type=" + $("#ruser").find("option:selected").attr("title");
	$.ajax({
		type: "POST",
		url: "/ajax/users/",
		data: data,
		success: function(res) {
            $("#addedusers").append(res);
		}
	})
}

function delRusers() {
    $("#addedusers").text("");
}

$("#type").change(function(){
    if ($("#type").val() == "0") {
        $("#global").show();
        $("#noiter").hide();
        $("#iter").hide();
    } else if ($("#type").val() == "1") {
        $("#global").hide();
        $("#noiter").show();
        $("#iter").hide();
    } else if ($("#type").val() == "2") {
        $("#global").hide();
        $("#noiter").hide();
        $("#iter").show();
    }
});
</script>