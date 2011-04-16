<div style="text-align: center; border-bottom: 1px dashed #FFF; padding-bottom: 10px; margin-bottom: 10px">

<p style="margin-bottom: 5px; font-weight: bold; font-size: 13px">
{{ now }}
</p>

<p style="margin-bottom: 5px">
    <span style="color: yellow">{{ ui.name }} {{ ui.soname }}</span>&nbsp;&nbsp;&nbsp;<a class="aleft" style="color: #F33; font-weight: bold" href="/exit/" title="выход"><img src="/img/exit.png" alt="exit" border="0" style="position: relative; top: 1px" /></a>
	<br />
    <span><b>Группа:</b>&nbsp;{{ ui.gname }}</span>

    {% if ui.admin %}
	<br />
    <span style="color: #F33">Администратор</span>
    {% endif %}
</p>

</div>