<form method="post" action="{{ registry.uri }}tt/add/">

<p style="font-size: 11px"><label><input type="checkbox" name="rall" value="1" id="Prall" />Выбрать всех</label></p>

<div id="utree">
	<ul id="Pstructure" class="filetree">{{ list }}</ul>
</div>

<input style="margin-top: 30px" type="submit" name="addUsersTask" value="Создать задачу" />
</form>