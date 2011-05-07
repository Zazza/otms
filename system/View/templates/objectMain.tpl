<div class="obj" id="win{{ obj.0.id }}" style="background-color: #EBF3FE; border: 1px solid #777">

<p style="margin-bottom: 10px; padding: 2px 0; font-family: Arial; font-size: 16px; font-weight: bold; overflow: hidden">
<span style="float: right; font-weight: bold"><a href="{{ uri }}objects/{{ obj.0.id }}/">Объект №{{ obj.0.id }}</a></span>
{% if ui.readonly == 0 %}
<img src="{{ uri }}img/plus-button.png" alt="plus" border="0" style="vertical-align: middle" />&nbsp;<a href="{{ uri }}tt/add/?oid={{ obj.0.id }}">добавить задачу</a>
{% endif %}
</p>

<div style="float: left; width: 220px">

<div class="info">

<div style="float: right">
    <a style="cursor: pointer; margin-right: 2px" onclick="getInfo({{ obj.0.id }})"><img src="{{ uri }}img/information-button.png" title="полные данные" alt="info" border="0" style="position: relative; top: 0" /></a>
    
    {% if ui.readonly == 0 %}
    <a href="{{ uri }}objects/edit/{{ obj.0.id }}/"><img src="{{ uri }}img/edititem.gif" title="изменить данные" alt="edit" border="0" style="position: relative; top: 1px" /></a>
    {% endif %}
</div>

{% for part in obj %}
<p><b>{{ part.field }}:</b>&nbsp;{{ part.val }}</p>
{% endfor %}

</div>

<div style="margin: 10px 0 0 5px">
<img src="{{ uri }}img/applications-stack.png" alt="" style="vertical-align: middle" />&nbsp;<a href="{{ uri }}tt/oid/{{ obj.0.id }}/">Все задачи</a>
</div>

<div style="margin: 10px 0 0 5px">
<span title="глобальные"><img border="0" style="vertical-align: middle" alt="" src="{{ uri }}img/clock.png" /><b>&nbsp;{{ numTroubles.global }}</b></span>
<span title="ограниченнные по времени" style="margin-left: 5px"><img border="0" style="vertical-align: middle" alt="" src="{{ uri }}img/alarm-clock.png" /><b>&nbsp;{{ numTroubles.time }}</b></span>
<span title="повторяющиеся" style="margin-left: 5px"><img border="0" style="vertical-align: middle" alt="" src="{{ uri }}img/calendar-blue.png" /><b>&nbsp;{{ numTroubles.iter }}</b></span>
<span title="закрытые" style="margin-left: 5px"><img border="0" style="vertical-align: middle" alt="" src="{{ uri }}img/flag.png" /><b>&nbsp;{{ numTroubles.close }}</b></span>
</div>

</div>

<div style="margin-left: 240px">

<div style="font-size: 14px; margin-bottom: 10px">
    <div style="float: right; cursor: pointer" id="showall" onclick="showAdv('#win{{ obj.0.id }}')"><img src="{{ uri }}img/tall-down-arrow.gif" alt="" border="0" style="vertical-align: middle" /> раскрыть всё <b>({{ numAdvInfo }} записей)</b></div>
    <div style="float: right; display: none; cursor: pointer" id="hideall" onclick="hideAdv('#win{{ obj.0.id }}')"><img src="{{ uri }}img/tall-up-arrow.gif" alt="" border="0" style="vertical-align: middle" /> свернуть <b>({{ numAdvInfo }} записей)</b></div>
    
    <span style="font-weight: bold; color: #000">Доп. информация:</span>
    {% if ui.readonly == 0 %}
    <span style="margin-left: 5px"><img src="{{ uri }}img/plus-button.png" alt="plus" border="0" style="vertical-align: middle" /> <a style="cursor: pointer; font-size: 11px" onclick="showAdvanced({{ obj.0.id }})">добавить</a></span>
    {% endif %}
</div>

{% for part in advInfo %}
{% if part.val %}
<div class="adv">
<p class="subadv">
    {% if ui.readonly == 0 %}
    <div style="float: right">
        <a style="cursor: pointer" onclick="editAdv({{ obj.0.id }}, {{ part.oaid }})" title="правка"><img src="{{ uri }}img/edititem.gif" alt="правка" border="0" style="vertical-align: middle" /></a>
        &nbsp;
        <a style="cursor: pointer" onclick="delAdvConfirm({{ obj.0.id }}, {{ part.oaid }})" title="удалить"><img src="{{ uri }}img/delete.png" alt="удалить" border="0" style="vertical-align: middle" /></a>
    </div>
    {% endif %}
    <b>{{ part.uname }} {{ part.usoname }} [{{ part.timestamp }}]</b>
</p>
<p>{{ part.val }}</p>

{% if part.tags %}
<div style="margin-top: 10px; color: #777">
<b>Теги:</b>
{% for tag in part.tags %}
<a href="{{ uri }}kb/?tag={{ tag }}">{{ tag }}</a>&nbsp;
{% endfor %}
</div>
{% endif %}

</div>
{% endif %}
{% endfor %}

</div>

</div>

<script type="text/javascript">
var win = "#win{{ obj.0.id }}";

var advhide = win + " .adv:not(:first)";

$(advhide).hide();
</script>