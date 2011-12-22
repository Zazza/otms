<p style="font-weight: bold; color: #999">Меню:</p>

{% if registry.ui.admin %}
{% if registry.args.0 == "mail" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}settings/mail/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/mail.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}settings/mail/">Почта</a>
</p>
{% endif %}

{% if registry.args.0 == "interface" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}settings/interface/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/interface.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}settings/interface/">Интерфейс</a>
</p>