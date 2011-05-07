{% if args == "users" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}settings/users/"><img style="vertical-align: middle" src="{{ uri }}img/left/users.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}settings/users/">Управление пользователями</a>
</p>

{% if args == "tt" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}settings/tt/"><img style="vertical-align: middle" src="{{ uri }}img/left/zones-stack.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}settings/tt/">Редактор групп для задач</a>
</p>

{% if args == "templates" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}settings/templates/"><img style="vertical-align: middle" src="{{ uri }}img/left/reports.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}settings/templates/">Шаблоны</a>
</p>