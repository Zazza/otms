<p style="font-weight: bold; color: #999">Меню:</p>

{% if registry.ui.admin %}
{% if registry.args.0 == "admin" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}users/admin/"><img style="vertical-align: middle" src="{{ registry.uri }}img/g.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}users/admin/">Управление пользователями</a>
</p>
{% endif %}

{% if not registry.args %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}users/"><img style="vertical-align: middle" src="{{ registry.uri }}img/gear.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}users/">Просмотр</a>
</p>