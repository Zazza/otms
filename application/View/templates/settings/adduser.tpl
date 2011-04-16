<form method="post" action="/settings/users/">

{% if err %}
{% for part in err %}
<p style="color: red">{{ part }}</p>
{% endfor %}
{% endif %}

<div style="margin-bottom: 50px">
<h3>Регистрация нового пользователя</h3>
<p><b>Логин</b></p>
<p><input name='login' type='text' size='60' value="{{ post.login }}" /></p>
<p><b>Имя</b></p><p><input name='name' type='text' size='60' value="{{ post.name }}" /></p>
<p><b>Фамилия</b></p><p><input name='soname' type='text' size='60' value="{{ post.soname }}" /></p>
<p><b>Email</b></p><p><input name='email' type='text' size='60' value="{{ post.email }}" /></p>
<p style="margin: 7px 0"><label><input name='admin' type='checkbox' {% if post.admin %} checked="checked" {% endif %} />&nbsp;<b>Администратор</b></label></p>
<p style="margin: 7px 0"><b>Группа</b>&nbsp;
<select name="group_name">
{% for part in group %}
<option value="{{ part.id }}" {% if post.group_name == part.id %} selected="selected" {% endif %}>{{ part.name }}</option>
{% endfor %}
</select>
</p>
<p><b>Пароль</b></p><p><input name='pass' type='password' /></p>
<p style="text-align: right"><input name='submit' type='submit' value='Готово' /></p>
</div>

</form>