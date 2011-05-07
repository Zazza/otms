{% if args.0 == "groups" or not args %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}stat/groups/"><img style="vertical-align: middle" src="{{ uri }}img/left/category.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}stat/groups/">По группам</a>
</p>

{% if args.0 == "rusers" %}
    <p class="sellmenu">
{% else %}
    <p class="lmenu">
{% endif %}
    <a href="{{ uri }}stat/rusers/"><img style="vertical-align: middle" src="{{ uri }}img/left/users.png" alt="" border="0" /></a>
    <a class="aleft" href="{{ uri }}stat/rusers/">По ответственным</a>
</p>