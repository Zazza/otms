<form method="post" action="{{ uri }}settings/users/gedit/{{ gid }}/">

<div style="margin-bottom: 50px">
<h3>Редактирование группы</h3>
<p><b>Имя группы</b></p>
<p><input name='group' type='text' size='60' value="{{ gname }}" /></p>
<p style="text-align: right"><input name='submit_group' type='submit' value='Готово' /></p>
</div>

</form>