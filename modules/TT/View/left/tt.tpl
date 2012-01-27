<p style="font-weight: bold; color: #999">Меню:</p>

{% if registry.args == "cal" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}tt/cal/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/calendar.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}tt/cal/">Календарь</a>
</p>

{% if registry.args == "groups" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}tt/groups/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/category.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}tt/groups/">Группы</a>
</p>

<hr />

<p style="font-weight: bold; color: #999">Актуальные задачи:</p>

{% if not registry.args %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}tt/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/task.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}tt/">Мои задачи [{{ registry.getNumTasks }}]</a>
</p>

{% if registry.args.1 == "me" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}tt/task/me/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/task.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}tt/task/me/">Я автор [{{ registry.getNumMeTasks }}]</a>
</p>

{% if registry.args == "draft" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}tt/draft/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/task-pencil.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}tt/draft/">Черновики [{{ registry.draftttnum }}]</a>
</p>
