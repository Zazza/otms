<div class="sel" style="width: 150px; margin-bottom: 10px; font-weight: bold"><img border="0" style="vertical-align: middle;" alt="plus" src="/img/plus-button.png" /> <a href="/settings/kb/addgroup/" style="text-decoration: none">Новая группа</a></div>

<table cellpadding="3" cellspacing="3">
<tr>
<td align="center" style="font-weight: bold; font-size: 10px">удалить</td>
<td align="center" style="font-weight: bold; font-size: 10px">изменить</td>
<td align="center" style="font-weight: bold; font-size: 10px">имя группы</td>
{% for part in group %}
<tr>
<td align="center" style="border: 1px solid #ccc">
    <a style="cursor: pointer" onclick="delGroupKbConfirm({{ part.id }})"><img style="vertical-align: middle" src="/img/delete.png" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    <a href="/settings/kb/edit/{{ part.id }}/"><img style="vertical-align: middle" src="/img/edititem.gif" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    {{ part.name }}
</td>
</tr>
{% else %}
<tr><td colspan="3" align="center" style="border: 1px solid #ccc">Пусто</td></tr>
{% endfor %}
</table>