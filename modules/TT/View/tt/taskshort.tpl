{% include "tt/taskinfo.tpl" %}

<div style="font-size: 11px; margin-bottom: 20px; overflow: hidden">

<div style="float: left; margin-right: 10px">
<a {% if data.0.close == 1 %}class="endtask"{% else %}class="livetask"{% endif %} href="{{ registry.uri }}tt/{{ data.0.id }}/">Задача {{ data.0.id }}</a>
<a style="cursor: pointer; margin-left: 10px" onclick="showTaskInfo('{{ data.0.id }}')"><img src="{{ registry.uri }}img/information-button.png" title="данные по задаче" alt="info" border="0" style="position: relative; top: 3px" /></a>

{% if data.0.imp == 1 %}
<div style="height: 12px; width: 20px; background-color: #00ffcc; text-align: center"><span style="font-size: 9px; position: relative; bottom: 4px">1/5</span></div>
{% elseif data.0.imp == 2 %}
<div style="height: 12px; width: 30px; background-color: #00ffcc; text-align: center"><span style="font-size: 9px; position: relative; bottom: 4px">2/5</span></div>
{% elseif data.0.imp == 3 %}
<div style="height: 12px; width: 50px; background-color: #ffcc00; text-align: center"><span style="font-size: 9px; position: relative; bottom: 4px">3/5</span></div>
{% elseif data.0.imp == 4 %}
<div style="height: 12px; width: 70px; background-color: #ff0000; text-align: center"><span style="font-size: 9px; position: relative; bottom: 4px">4/5</span></div>
{% elseif data.0.imp == 5 %}
<div style="height: 12px; width: 90px; background-color: #ff0000; text-align: center"><span style="font-size: 9px; position: relative; bottom: 4px">5/5</span></div>
{% endif %}

</div>

{% if data.0.mail_id %}
<iframe style="border: 1px solid #EEE" src="{{ registry.siteName }}{{ registry.uri }}mail/load/?mid={{ data.0.mail_id }}&part=1" frameborder="0" width="700px" height="90%"></iframe>
{% else %}
<div style="margin-left: 140px; font-size: 12px">{{ data.0.text }}</div>
{% endif %}

</div>