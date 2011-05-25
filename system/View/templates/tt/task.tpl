<div class="obj" style="margin-bottom: 40px">

<p style="margin-bottom: 20px; padding: 2px 0; font-family: Arial; font-size: 16px; font-weight: bold; overflow: hidden">
<span style="float: right">

{% if ui.readonly == 0 %}
{% if uid == data.0.who %}
{% if data.0.close == 0 %}
<a href="{{ uri }}tt/edit/{{ data.0.id }}/" title="правка задачи"><img src="{{ uri }}img/edititem.gif" alt="" style="vertical-align: middle; margin: 2px 5px 0 0" border="0" /></a>
{% endif %}
{% endif %}
{% endif %}

    {% if data.0.close == 1 %}
    <img src="{{ uri }}img/flag.png" alt="" style="vertical-align: middle; margin-right: 5px" /> 
    {% else %}
    {% if data.0.spam != 0 %}
    <a style="cursor: pointer" onclick="spam({{ data.0.id }})" title="отписаться от рассылки по задаче"><img src="{{ uri }}img/mail--minus.png" alt="отписаться" style="vertical-align: middle; margin-right: 5px" /></a>
    {% else %}
    <a style="cursor: pointer" onclick="spam({{ data.0.id }})" title="подписаться на рассылку по задаче"><img src="{{ uri }}img/mail--plus.png" alt="подписаться" style="vertical-align: middle; margin-right: 5px" /></a>
    {% endif %}
    {% endif %}
    
    {% if data.0.secure %}
    <a href="{{ uri }}tt/{{ data.0.id }}/" style="color: red" title="доступ только адресованным">Задача {{ data.0.id }}</a> <img alt="замок" src="{{ uri }}img/lock.png" style="vertical-align: middle" />
    {% else %}
    <a class="title" style="{% if data.0.close == 1 %}color: red{% else %}color: green{% endif %}" href="{{ uri }}tt/{{ data.0.id }}/">Задача {{ data.0.id }}</a>
    {% endif %}
</span>

<span class="title">{{ author.soname }} {{ author.name }} [{{ data.0.start }}]</span>
</p>

<div style="margin-bottom: 10px; overflow: hidden">

{% if notObj %}
<div style="float: left; width: 220px">
<div class="info">

<!-- объект -->
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
</div>
{% endif %}
<!-- END объект -->

<!-- группа и важность -->
<div style="float: left; width: 130px; text-align: left">
<p style="margin-bottom: 5px"><b>Группа: {{ data.0.group }}</b></p>

<p style="margin: 5px 0"><b>Важность:
{% if data.0.imp > 3 %}
    <span style="color: red"><b>{{ data.0.imp }}</b></span>
{% else %}
    <span><b>{{ data.0.imp }}</b></span>
{% endif %}
</b></p>
</div>
<!-- END группа и важность -->

<!-- ответственные и закрывший задачу -->
<div style="float: left; width: 150px; text-align: center; margin: 0 0 10px 5px">
{% set flag = 1 %}
<p style="margin-bottom: 5px"><b>Ответственные:</b></p>
{% for part in ruser %}

    {% if part.name != '' %}
    {% set flag = 0 %}
    {% endif %}
    
    <p>{{ part.name }} {{ part.soname }}</p>
    
{% endfor %}

{% if flag == 1 %}
<p style="color: red">не назначены</p>
{% endif %}

{% if data.0.close == 1 %}
{% if cuser.name %}
<p style="margin: 10px 0 5px 0"><b>Закрыл задачу:</b></p>
<p style="background-color: #D9A444; padding: 2px 0">{{ cuser.name }} {{ cuser.soname }}</p>
{% endif %}
{% endif %}
</div>
<!-- END ответственные и закрывший задачу -->

<!-- сроки -->
<div style="float: right; text-align: center">
<p style="margin-bottom: 5px"><b>Время/сроки выполнения:</b></p>

{% if data.0.type == "0" %}
<p style="margin-bottom: 5px"><img border="0" src="{{ uri }}img/clock.png" alt="" style="margin-bottom: 7px" /></p>

<p style="margin-bottom: 5px"><img title="начало" border="0" src="{{ uri }}img/flag-blue.png" alt="" style="vertical-align: middle" />&nbsp;{{ data.0.openingF }}</p>

{% if data.0.close == 1 %}
<p style="margin-bottom: 5px"><img title="дата закрытия" border="0" src="{{ uri }}img/flag.png" alt="" style="vertical-align: middle" />&nbsp;{{ data.0.endingF }}</p>
{% endif %}

{% endif %}

{% if data.0.type == "1" %}
<p style="margin-bottom: 5px">
<img border="0" src="{{ uri }}img/alarm-clock.png" alt="" style="position: relative; top: 4px" />
{% if data.0.expire %}
<span style="padding: 2px 4px; background-color: orange; margin-left: 2px; font-weight: bold">просроченная задача</span>
</p>
{% endif %}

<p style="margin-bottom: 5px"><img title="начало" border="0" src="{{ uri }}img/flag-blue.png" alt="" style="vertical-align: middle" />&nbsp;{{ data.0.openingF }}</p>

<p style="margin-bottom: 5px">продолжительностью <b>{{ data.0.deadline }} {{ data.0.deadline_date }}</b></p>

{% if data.0.close == 1 %}
<p style="margin-bottom: 5px"><img title="дата закрытия" border="0" src="{{ uri }}img/flag.png" alt="" style="vertical-align: middle" />&nbsp;{{ data.0.endingF }}</p>
{% endif %}

{% endif %}

{% if data.0.type == "2" %}
<p style="margin-bottom: 5px"><img border="0" alt="" src="{{ uri }}img/calendar-blue.png" style="margin-bottom: 7px" /></p>

<p style="margin-bottom: 5px"><img title="начало" border="0" src="{{ uri }}img/flag-blue.png" alt="" style="vertical-align: middle" />&nbsp;{{ data.0.openingF }}</p>

<p style="margin-bottom: 5px">каждый(е) <b>{{ data.0.iteration }} {% if data.0.timetype_iteration == "day" %}дней{% elseif data.0.timetype_iteration == "month" %}месяцев{% endif %}</b></p>
{% if data.0.deadline != 0 %}
<p style="margin-bottom: 5px">продолжительностью <b>{{ data.0.deadline }} {{ data.0.deadline_date }}</b></p>
{% endif %}

{% if data.0.close == 1 %}
<p style="margin-bottom: 5px"><img title="дата закрытия" border="0" src="{{ uri }}img/flag.png" alt="" style="vertical-align: middle" />&nbsp;{{ data.0.endingF }}</p>
{% endif %}

{% endif %}
</div>
<!-- END сроки -->

</div>

<div class="sel" style="clear: both">{{ data.0.text }}</div>

<p style="margin-top: 20px">
<span style="float: right"><img src="{{ uri }}img/user-medium.png" alt="" style="vertical-align: middle" /> <a href="{{ uri }}tt/{{ data.0.id }}/">комментарии</a> ({{ numComments }})</span>

{% if ui.readonly == 0 %}
{% if data.0.close == 0 %}
<span class="sel" style="float: left; font-weight: bold"><img src="{{ uri }}img/edititem.gif" alt="" style="vertical-align: middle" /> <a style="cursor: pointer; text-decoration: none" onclick="showEditTask({{ data.0.id }})">комментировать</a></span>

<span class="sel" style="margin-left: 10px; float: left; font-weight: bold"><img src="{{ uri }}img/inbox-download.png" alt="" style="vertical-align: middle" /> <a style="cursor: pointer; text-decoration: none" onclick="closeTask({{ data.0.id }})">Закрыть задачу</a></span>
{% endif %}
{% endif %}
</p>

</div>