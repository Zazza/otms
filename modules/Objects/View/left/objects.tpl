<p style="font-weight: bold; color: #999">Меню:</p>

{% if registry.ui.admin %}
{% if registry.args.0 == "admin" or registry.args.0 == "templates" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}objects/admin/"><img style="vertical-align: middle" src="{{ registry.uri }}img/g.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}objects/admin/">Управление объектами</a>
</p>
{% endif %}

{% if registry.args.0 == "forms" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}objects/forms/"><img style="vertical-align: middle" src="{{ registry.uri }}img/application-form.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}objects/forms/">Управление формами</a>
</p>

{% if not registry.args %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}objects/"><img style="vertical-align: middle" src="{{ registry.uri }}img/gear.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}objects/">Просмотр</a>
</p>