<p style="font-weight: bold; color: #999">Меню:</p>

<p><b>Поиск:</b></p>

{% if registry.args.0 == "objects" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}find/objects/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/books-stack.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}find/objects/">Объекты&nbsp;[{{ num.obj }}]</a>
</p>

{% if registry.args.0 == "tasks" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}find/tasks/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/task.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}find/tasks/">Задачи&nbsp;[{{ num.tasks }}]</a>
</p>

{% if registry.args.0 == "adv" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}find/adv/"><img style="vertical-align: middle" src="{{ registry.uri }}img/information-button.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}find/adv/">Доп. инфо&nbsp;[{{ num.advs }}]</a>
</p>