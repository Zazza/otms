{% if ui.readonly == 0 %}

{% if args == "add" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}tt/add/"><img border="0" src="{{ uri }}img/plus-button.png" alt="" style="vertical-align: middle" /></a>
    <a class="aleft" href="{{ uri }}tt/add/">Создать задачу</a>
    </p>
{% endif %}

{% if args == "cal" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}tt/cal/"><img style="vertical-align: middle" src="{{ uri }}img/left/calendar.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}tt/cal/">Календарь</a>
</p>

<hr />

{% if args == "index" and menu != "all" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}tt/list/"><img style="vertical-align: middle" src="{{ uri }}img/left/task.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}tt/">Мои задачи [{{ ui.ttnum }}]</a>
</p>

{% if ui.admin %}
{% if args == "index" and menu == "all" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}tt/list/"><img style="vertical-align: middle" src="{{ uri }}img/left/task-select.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}tt/task/all/">Все задачи [{{ ui.allttnum }}]</a>
</p>
{% endif %}

{% if ui.readonly == 0 %}
{% if args == "new" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}tt/new/"><img style="vertical-align: middle" src="{{ uri }}img/left/task--plus.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}tt/new/">Без ответственных [{{ ui.nobodyttnum }}]</a>
</p>
{% endif %}