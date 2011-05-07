<table cellpadding="3" cellspacing="3" style="margin-bottom: 50px">
{% for part in data %}

    <tr>
    <td colspan="3" align="left">
    {% if part.uid %}
        <img border="0" alt="" src="{{ uri }}img/user-white.png" style="vertical-align: middle; margin-right: 10px" /><a href="{{ uri }}stat/rusers/{{ part.uid }}/" class="none">{{ part.name }} {{ part.soname }} [{{ part.count }}]</a>
    {% else %}
        <img border="0" alt="" src="{{ uri }}img/user-white.png" style="vertical-align: middle; margin-right: 10px" /><a href="{{ uri }}stat/rusers/0/" class="none">Без ответственных [{{ part.count }}]</a>
    {% endif %}
    </td>
    </tr>
        
{% endfor %}
</table>