<p><b>Поиск:</b></p>

{% if args == "objects" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}find/objects/"><img style="vertical-align: middle" src="{{ uri }}img/left/books-stack.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}find/objects/">Объекты&nbsp;[{{ num.obj }}]</a>
</p>

{% if args == "tasks" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}find/tasks/"><img style="vertical-align: middle" src="{{ uri }}img/left/task.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}find/tasks/">Задачи&nbsp;[{{ num.tasks }}]</a>
</p>

{% if args == "adv" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}find/adv/"><img style="vertical-align: middle" src="{{ uri }}img/information-button.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}find/adv/">Доп. инфо&nbsp;[{{ num.advs }}]</a>
</p>