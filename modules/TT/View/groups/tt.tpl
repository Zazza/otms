{% if registry.ui.admin %}
<div style="margin-bottom: 20px">
<img border="0" src="{{ registry.uri }}img/g.png" alt="" style="vertical-align: middle" />
<a style="cursor: pointer" onclick="showGroupsAdmin()">Управление группами</a>
</div>

<div id="groupAdmin" style="display: none" class="obj">

<div class="button" style="width: 150px; margin-bottom: 10px; font-weight: bold"><img border="0" style="vertical-align: middle;" alt="plus" src="{{ registry.uri }}img/plus-button.png" />&nbsp;<a href="{{ registry.uri }}tt/groups-admin/add/" style="text-decoration: none">Новая группа</a></div>

<table cellpadding="3" cellspacing="3">
<tr>
<td align="center" style="font-weight: bold; font-size: 10px">удалить</td>
<td align="center" style="font-weight: bold; font-size: 10px">изменить</td>
<td align="center" style="font-weight: bold; font-size: 10px">имя группы</td>
{% for part in group %}
<tr>

<td align="center" style="border: 1px solid #ccc">
    <a style="cursor: pointer" onclick="delGroupTtConfirm({{ part.id }})"><img style="vertical-align: middle" src="{{ registry.uri }}img/delete.png" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    <a href="{{ registry.uri }}tt/groups-admin/edit/{{ part.id }}/"><img style="vertical-align: middle" src="{{ registry.uri }}img/edititem.gif" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    {{ part.name }}
</td>
</tr>
{% else %}
<tr><td colspan="3" align="center" style="border: 1px solid #ccc">Пусто</td></tr>
{% endfor %}
</table>

</div>

{% endif %}

<script type="text/javascript">
function showGroupsAdmin() {
	if ($("#groupAdmin").css("display") == "none") {
		$("#groupAdmin").slideDown();
	} else {
		$("#groupAdmin").slideUp();
	}
}
</script>