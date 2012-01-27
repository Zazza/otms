<p style="font-weight: bold; color: #999">Меню:</p>

{% if not registry.args.0 or registry.args.0 == "profile" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}profile/profile/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/xfn.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}profile/profile/">Личные данные</a>
</p>

{% if registry.args.0 == "skin" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ registry.uri }}profile/skin/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/interface.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ registry.uri }}profile/skin/">Скин</a>
</p>