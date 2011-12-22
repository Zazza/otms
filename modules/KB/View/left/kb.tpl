<p style="font-weight: bold; color: #999">Меню:</p>

{% if registry.args == "add" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}kb/add/"><img style="vertical-align: middle" src="{{ registry.uri }}img/document-plus.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}kb/add/">Добавить информацию</a>
</p>

{% if not registry.args %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}kb/"><img style="vertical-align: middle" src="{{ registry.uri }}img/information-button.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}kb/">Просмотр</a>
</p>