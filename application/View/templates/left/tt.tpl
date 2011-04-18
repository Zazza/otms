{% if ui.readonly == 0 %}
<p>
    <a href="/tt/add/"><img border="0" src="/img/plus-button.png" alt="" style="vertical-align: middle" /></a>
    <a class="aleft" href="/tt/add/">Создать задачу</a>
</p>
{% endif %}

<p>
    <a href="/tt/cal/"><img style="vertical-align: middle" src="/img/left/calendar.png" alt="" border="0" /></a>
    <a class="aleft" href="/tt/cal/">Календарь</a>
</p>

<p>
    <a href="/tt/list/"><img style="vertical-align: middle" src="/img/left/task.png" alt="" border="0" /></a>
    <a class="aleft" href="/tt/">Мои задачи [{{ ui.ttnum }}]</a>
</p>

{% if ui.admin %}
<p>
    <a href="/tt/list/"><img style="vertical-align: middle" src="/img/left/task-select.png" alt="" border="0" /></a>
    <a class="aleft" href="/tt/?task=all">Все задачи</a>
</p>
{% endif %}

{% if ui.readonly == 0 %}
<p>
    <a href="/tt/new/"><img style="vertical-align: middle" src="/img/left/task--plus.png" alt="" border="0" /></a>
    <a class="aleft" href="/tt/new/">Без ответственных</a>
</p>
{% endif %}