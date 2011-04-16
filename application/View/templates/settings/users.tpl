<div class="sel" style="width: 150px; margin-bottom: 10px; font-weight: bold"><img border="0" style="vertical-align: middle;" alt="plus" src="/img/plus-button.png" /> <a href="/settings/users/addgroup/" style="text-decoration: none">Новая группа</a></div>

<table cellpadding="3" cellspacing="3" style="margin-bottom: 50px">
<tr>
<td align="center" style="font-weight: bold; font-size: 10px">удалить</td>
<td align="center" style="font-weight: bold; font-size: 10px">изменить</td>
<td align="center" style="font-weight: bold; font-size: 10px">имя группы</td>
{% for part in group %}
<tr>
<td align="center" style="border: 1px solid #ccc">
    <a style="cursor: pointer" onclick="delGroupConfirm({{ part.id }})"><img style="vertical-align: middle" src="/img/delete.png" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    <a href="/settings/users/gedit/{{ part.id }}/"><img style="vertical-align: middle" src="/img/edititem.gif" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    {{ part.name }}
</td>
</tr>
{% else %}
<tr><td colspan="3" align="center" style="border: 1px solid #ccc">Пусто</td></tr>
{% endfor %}
</table>

<div class="sel" style="width: 170px; margin-bottom: 10px; font-weight: bold"><img border="0" style="vertical-align: middle;" alt="plus" src="/img/plus-button.png" /> <a href="/settings/users/adduser/" style="text-decoration: none">Новый пользователь</a></div>

<table cellpadding="3" cellspacing="3">
<tr>
<td align="center" style="font-weight: bold; font-size: 10px">удалить</td>
<td align="center" style="font-weight: bold; font-size: 10px">изменить</td>
<td align="center" style="font-weight: bold; font-size: 10px">логин</td>
<td align="center" style="font-weight: bold; font-size: 10px">email</td>
<td align="center" style="font-weight: bold; font-size: 10px">имя</td>
<td align="center" style="font-weight: bold; font-size: 10px">фамилия</td>
<td align="center" style="font-weight: bold; font-size: 10px">группа</td>
<td align="center" style="font-weight: bold; font-size: 10px">администратор</td>
</tr>

{% for part in list %}
<tr>

<td align="center" style="border: 1px solid #ccc">
    <a style="cursor: pointer" onclick="delUserConfirm({{ part.id }})"><img style="vertical-align: middle" src="/img/delete.png" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    <a href="/settings/users/edit/{{ part.id }}/"><img style="vertical-align: middle" src="/img/edititem.gif" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    {{ part.login }}
</td>

<td align="center" style="border: 1px solid #ccc">
    {{ part.email }}
</td>

<td align="center" style="border: 1px solid #ccc">
    {{ part.name }}
</td>

<td align="center" style="border: 1px solid #ccc">
    {{ part.soname }}
</td>

<td align="center" style="border: 1px solid #ccc">
    {{ part.gname }}
</td>

<td align="center" style="border: 1px solid #ccc">
    {% if part.admin %}
        <span style="color: red">+</span>
    {% else %}
        <span style="color: #AAA">-</span>
    {% endif %}
</td>

</tr>
{% else %}
<tr><td colspan="3" align="center" style="border: 1px solid #ccc">Пусто</td></tr>
{% endfor %}
</table>