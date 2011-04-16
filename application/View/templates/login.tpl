{% if err %}
<p style="color: red">Неверный логин/пароль</p>
{% endif %}

<div style="margin-left: 100px">
<form action="/login/" method="post">
<p style="font-weight: bold">Логин</p>
<p><input type="text" name="login" /></p>
<p style="font-weight: bold">Пароль</p>
<p><input type="password" name="pass" /></p>
<p><input type="submit" name="submit" value="Войти" /></p>
</form>
</div>