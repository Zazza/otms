{% if err %}
<p style="color: red">Неверный логин/пароль</p>
{% endif %}

<div style="margin-left: 100px; float: left; padding: 10px; margin-top: 50px" class="sel">
<form action="{{ uri }}login/" method="post">
<p style="font-weight: bold">Логин</p>
<p><input type="text" name="login" /></p>
<p style="font-weight: bold">Пароль</p>
<p><input type="password" name="pass" /></p>
<p><input type="submit" name="submit" value="Войти" /></p>
</form>
</div>

<div style="margin: 80px 0 0 350px">
<p>логин/пароль для входа <b>readonly/readonly</b></p>
<p>Пользователь readonly! Большая часть функциональночти не доступна.</p>
</div>