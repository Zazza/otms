{% if data.0.gid != "0" %}
<div class="obj" style="border: 2px solid red">
{% else %}
<div class="obj" style="border: 2px solid green">
{% endif %}

<p style="margin-bottom: 20px; padding: 2px 0; font-family: Arial; font-size: 16px; font-weight: bold; overflow: hidden">
<span style="float: right">
    {% if data.0.gid != 0 %}
    <img src="/img/flag.png" alt="" style="vertical-align: middle; margin-right: 5px" /> 
    {% else %}
    {% if data.0.spam != 0 %}
    <a style="cursor: pointer" onclick="spam({{ data.0.id }})" title="отписаться от рассылки по задаче"><img src="/img/mail--minus.png" alt="отписаться" style="vertical-align: middle; margin-right: 5px" /></a>
    {% else %}
    <a style="cursor: pointer" onclick="spam({{ data.0.id }})" title="подписаться на рассылку по задаче"><img src="/img/mail--plus.png" alt="подписаться" style="vertical-align: middle; margin-right: 5px" /></a>
    {% endif %}
    {% endif %}
    
    {% if data.0.secure %}
    <a href="/tt/{{ data.0.id }}/" style="color: red" title="доступ только адресованным">Задача {{ data.0.id }}</a> <img alt="замок" src="/img/lock.png" style="vertical-align: middle" />
    {% else %}
    <a href="/tt/{{ data.0.id }}/">Задача {{ data.0.id }}</a>
    {% endif %}
</span>

{% if uid == data.0.who %}
{% if data.0.gid == 0 %}
<span style="float: right; margin-right: 10px"><a href="/tt/edit/{{ data.0.id }}/" title="правка задачи"><img src="/img/edititem.gif" alt="" style="margin-top: 2px" border="0" /></a></span>
{% endif %}
{% endif %}

<span>{{ author.soname }} {{ author.name }} [{{ data.0.start }}]</span>
</p>

<div style="border: 1px solid #EEE; margin-bottom: 10px; overflow: hidden">

{% if notObj %}
<div style="float: left">
<div class="info">
{% for part in obj %}
<p><b>{{ part.field }}:</b>&nbsp;{{ part.val }}</p>
{% endfor %}
</div>

<p style="margin-top: 10px">
<img src="/img/information-button.png" alt="info" border="0" style="vertical-align: middle" /> <a style="cursor: pointer" onclick="getInfo({{ obj.0.id }})">полные данные</a>
<br />
<img src="/img/edit.png" alt="edit" border="0" style="vertical-align: middle" /> <a href="/objects/edit/{{ obj.0.id }}/">изменить данные</a>
</p>

</div>
{% endif %}

<div style="float: left; width: 120px; text-align: center">
<p style="margin-bottom: 5px"><b>Статус:</b></p>
{% if data.0.gid == "0" %}<p style="color: green">открытая</p>{% endif %}
{% if data.0.gid != "0" %}<p style="color: blue">закрытая, группа <b>"{{ data.0.group }}"</b></p>{% endif %}

<p style="margin: 5px 0"><b>Важность:</b></p>
<p style="font-weight: bold">{{ data.0.imp }}</p>
</div>

<div style="float: left; width: 150px; text-align: center; margin-bottom: 10px">
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
</div>

<div style="float: right; text-align: center">
<p style="margin-bottom: 5px"><b>Время/сроки выполнения:</b></p>

{% if data.0.type == "0" %}
<img border="0" src="/img/clock.png" alt="" style="vertical-align: middle" />
<p>начиная с <b>{{ data.0.opening }}</b></p>

{% if data.0.gid != 0 %}
<p style="color: red">завершено: <b>{{ data.0.ending }}</b></p> 
{% endif %}

{% endif %}

{% if data.0.type == "1" %}
<img border="0" src="/img/alarm-clock.png" alt="" style="vertical-align: middle" />
<p style="color: blue">выполняется один раз</p>
<p>начиная с <b>{{ data.0.opening }}</b></p>
<p>продолжительностью <b>{{ data.0.deadline }} {{ data.0.deadline_date }}</b></p>

{% if data.0.gid != 0 %}
<p style="color: red">завершено: <b>{{ data.0.ending }}</b></p> 
{% endif %}

{% endif %}

{% if data.0.type == "2" %}
<img border="0" alt="" src="/img/calendar-blue.png" style="position: relative; top: 3px" />
<p style="color: orange">выполняется периодически</p>
<p>начиная с <b>{{ data.0.opening }}</b></p>
<p>каждый(е) <b>{{ data.0.iteration }} дней</b></p>
{% if data.0.deadline != 0 %}
<p>продолжительностью <b>{{ data.0.deadline }} {{ data.0.deadline_date }}</b></p>
{% endif %}

{% if data.0.gid != 0 %}
<p style="color: red">завершено: <b>{{ data.0.ending }}</b></p> 
{% endif %}

{% endif %}
</div>

</div>

<div class="sel" style="clear: both">{{ data.0.text }}</div>

<p style="margin-top: 20px">
<span style="float: right"><img src="/img/user-medium.png" alt="" style="vertical-align: middle" /> <a href="/tt/{{ data.0.id }}/">комментарии</a> ({{ numComments }})</span>

{% if data.0.gid == 0 %}
<span class="sel" style="float: left; font-weight: bold"><img src="/img/edititem.gif" alt="" style="vertical-align: middle" /> <a style="cursor: pointer; text-decoration: none" onclick="showEditTask({{ data.0.id }})">комментировать</a></span>

<span class="sel" style="margin-left: 10px; float: left; font-weight: bold"><img src="/img/inbox-download.png" alt="" style="vertical-align: middle" /> <a style="cursor: pointer; text-decoration: none" onclick="closeTask({{ data.0.id }})">Закрыть задачу</a></span>
{% endif %}
</p>

</div>