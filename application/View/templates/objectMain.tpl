<div class="obj" id="win{{ obj.0.id }}" style="border: 2px solid orange">

<p style="margin-bottom: 20px; padding: 2px 0; font-family: Arial; font-size: 16px; font-weight: bold; overflow: hidden">
<span style="float: right; font-weight: bold"><a href="/objects/{{ obj.0.id }}/">Информация об объекте</a></span>
{% if ui.readonly == 0 %}
<img src="/img/plus-button.png" alt="plus" border="0" style="vertical-align: middle" /> <a href="/tt/add/?oid={{ obj.0.id }}">добавить задачу</a>
{% endif %}
</p>

<div style="float: left; width: 220px">

<div style="font-size: 14px; margin-bottom: 10px">
    <span style="font-weight: bold; color: #000">Данные:</span>
</div>

<div class="info">
{% for part in obj %}
<p><b>{{ part.field }}:</b>&nbsp;{{ part.val }}</p>
{% endfor %}
</div>

<p style="margin-top: 10px">
<img src="/img/information-button.png" alt="info" border="0" style="vertical-align: middle" /> <a style="cursor: pointer" onclick="getInfo({{ obj.0.id }})">полные данные</a>
<br />
{% if ui.readonly == 0 %}
<img src="/img/edit.png" alt="edit" border="0" style="vertical-align: middle" /> <a href="/objects/edit/{{ obj.0.id }}/">изменить данные</a>
{% endif %}
</p>

<p style="margin-top: 10px">
<img src="/img/applications-stack.png" alt="" style="vertical-align: middle" /> <a href="/tt/?oid={{ obj.0.id }}">Все задачи</a>
</p>
<p style="margin-left: 20px"><b>Открытые:</b> {{ numTroubles.open }}</p>
<p style="margin-left: 20px"><b>Закрытые:</b> {{ numTroubles.close }}</p>
</div>

<div style="margin-left: 240px">

<div style="font-size: 14px; margin-bottom: 10px">
    <div style="float: right; cursor: pointer" id="showall" onclick="showAdv('#win{{ obj.0.id }}')"><img src="/img/tall-down-arrow.gif" alt="" border="0" style="vertical-align: middle" /> раскрыть всё <b>({{ numAdvInfo }} записей)</b></div>
    <div style="float: right; display: none; cursor: pointer" id="hideall" onclick="hideAdv('#win{{ obj.0.id }}')"><img src="/img/tall-up-arrow.gif" alt="" border="0" style="vertical-align: middle" /> свернуть <b>({{ numAdvInfo }} записей)</b></div>
    
    <span style="font-weight: bold; color: #000">Доп. информация:</span>
    {% if ui.readonly == 0 %}
    <br />
    <span><img src="/img/plus-button.png" alt="plus" border="0" style="vertical-align: middle" /> <a style="cursor: pointer; font-size: 11px" onclick="showAdvanced({{ obj.0.id }})">добавить</a></span>
    {% endif %}
</div>

{% for part in advInfo %}
{% if part.val %}
<div class="adv">
<p class="subadv">
    {% if ui.readonly == 0 %}
    <div style="float: right">
        <a style="cursor: pointer" onclick="editAdv({{ obj.0.id }}, {{ part.oaid }})" title="правка"><img src="/img/edititem.gif" alt="правка" border="0" style="vertical-align: middle" /></a>
        &nbsp;
        <a style="cursor: pointer" onclick="delAdvConfirm({{ obj.0.id }}, {{ part.oaid }})" title="удалить"><img src="/img/delete.png" alt="удалить" border="0" style="vertical-align: middle" /></a>
    </div>
    {% endif %}
    <b>{{ part.uname }} {{ part.usoname }} [{{ part.timestamp }}]</b>
</p>
<p></p>{{ part.val }}</p>
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