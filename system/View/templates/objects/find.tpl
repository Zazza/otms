<form action="{{ uri }}objects/" method="post" name="form_list_obj">

<ul id="structure" class="filetree">{{ list }}</ul>

<input name="move_confirm" id="move_confirm" type="hidden" value="no" />
<input name="tName" id="tName" type="hidden" value="" />
<input name="tTypeName" id="tTypeName" type="hidden" value="" />

<hr />

<img src="{{ uri }}img/sort--arrow.png" alt="" border="0" style="vertical-align: middle; margin-right: 4px" /><a style='cursor: pointer' onclick='moveConfirm()'>переместить</a>

<div id="moveDialog" title="Перемещение" style="display: none">
    <p>Действительно переместить объекты?</p>
    
    <select name="template" id="template">
        {% for template in templates %}
        <option value="{{ template.id }}">{{ template.name }}</option>
        {% endfor %}
    </select>
    
    {% for template in templates %}
    
    <select name="tType" id="tree_{{ template.id }}" onclick="setTpl()" style="display: none">
        <option value="0">..</option>
        {% for part in tree[template.id] %}
        <option value="{{ part.id }}">{{ part.name }}</option>
        {% endfor %}
    </select>
    
    {% endfor %}
</div>

</form>

<script type="text/javascript">
var id = $("#template").val();
$("#tree_" + id).show();

$('#tName').val(id);
$('#tTypeName').val($("#tree_" + id).val());

$("#template").change(function() {
    var tid = $("#template").val();
    
    selShow(tid);
});

function selShow(id) {
    for (var i = 0; i <= {{ numT }}; i++) {
        $("#tree_" + i).hide();
    }
    
    $("#tree_" + id).show();
    
    $('#tName').val(id);
    $('#tTypeName').val($("#tree_" + id).val());
}

function setTpl() {
    var id = $("#template").val();
    
    $('#tName').val(id);
    $('#tTypeName').val($("#tree_" + id).val());
}

function moveConfirm() {
    $("#moveDialog").dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() {
                $('#move_confirm').val($("#template").val());
                document.forms["form_list_obj"].submit();
            }
		},
		width: 440
	})
}
</script>