<table cellpadding="3" cellspacing="3" style="margin-bottom: 50px">
{% for part in data %}

    <tr>
    <td colspan="3" align="left">

    {% if part.gid != 0 %}
        <img border="0" alt="" src="{{ uri }}img/g.png" style="vertical-align: middle; margin-right: 10px" /><a class="none" href="{{ uri }}stat/groups/{{ part.gid }}/">{{ part.name }} [{{ part.count }}]</a>
    {% else %}
        <img border="0" alt="" src="{{ uri }}img/g.png" style="vertical-align: middle; margin-right: 10px" /><a class="none" href="{{ uri }}stat/groups/0/">Открытые задачи [{{ part.count }}]</a>
    {% endif %}
    
    </td>
    </tr>
    
{% endfor %}
</table>