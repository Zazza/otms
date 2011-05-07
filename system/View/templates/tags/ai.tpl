<b>Тег:</b> {{ tag|e }}

{% for part in ai %}

<div class="sel" style="margin-top: 20px">

    {% if ui.readonly == 0 %}
    <div style="float: right">
        <a style="cursor: pointer" onclick="editAdv({{ part.oid }}, {{ part.id }})" title="правка"><img src="{{ uri }}img/edititem.gif" alt="правка" border="0" style="vertical-align: middle" /></a>
        &nbsp;
        <a style="cursor: pointer" onclick="delAdvConfirm({{ part.oid }}, {{ part.id }})" title="удалить"><img src="{{ uri }}img/delete.png" alt="удалить" border="0" style="vertical-align: middle" /></a>
    </div>
    {% endif %}

    <p>{{ part.param }}</p>
    <p>{{ part.val }}</p>

</div>

{% endfor %}