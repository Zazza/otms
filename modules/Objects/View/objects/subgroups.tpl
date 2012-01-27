<div style="padding: 10px 0 30px 0">

<span class="button" style="margin-right: 10px">
	<img style="vertical-align: middle" alt="add object" border="0" src="{{ registry.uri }}img/plus-button.png" />
	<a href="{{ registry.uri }}objects/add/?p={{ gid }}">Добавить объект</a>
</span>

{% if mail %}
<span class="button" style="margin-right: 10px">
	<img style="vertical-align: middle" src="{{ registry.uri }}img/left/mail-plus.png" alt="mail" border="0" />
	<a style='cursor: pointer' onclick='writeMail()'>написать письмо</a>
</span>
{% endif %}

<span class="button">
	<img style="vertical-align: middle" src="{{ registry.uri }}img/document-tree.png" alt="move" border="0" />
	<a style='cursor: pointer' onclick='moveConfirm()'>переместить</a>
</span>

<div id="moveGroups" style="display: none" title="Группа для перемещения">
	<select id="selsub">
	{% for part in tt %}
	<option value="{{ part.id }}">{{ part.name }}</option>
	{% endfor %}
	</select>
</div>

</div>

<div id="objStructure">

{% for obj in objs %}
<div class="obj" style="overflow: visible; border-bottom: 1px solid #EEE; border-left: 1px solid #EEE; border-right: 1px solid #EEE">

<div style="min-height: 100px">

<div style="float: left; width: 100px; margin-top: 8px">
	<div style="width: 80px; border-bottom: 1px solid #EEE; margin-bottom: 15px">
		<input type="checkbox" class="objs tgroup{{ obj.0.type_id }}" id="obj[{{ obj.0.id }}]" name="obj[{{ obj.0.id }}]" />
		<label for="obj[{{ obj.0.id }}]">выбрать</label>
	</div>
	
	<div style="height: 20px">
		<ul class="dropdown taskbutton">
			<li><a href="#" class="dir">Формы</a>
				<ul>
				{% for form in forms %}
				<div class="taskadv"><a href="{{ registry.uri }}objects/setform/?oid={{ obj.0.id }}&fid={{ form.id }}">{{ form.name }}</a></div>
				{% endfor %}
				</ul>
			</li>
		</ul>
	</div>

	<div style="overflow: hidden; margin-top: 10px; line-height: 14px">
	<img src="{{ registry.uri }}img/plus-small.png" alt="plus" border="0" style="position: relative; top: 5px" />
	<a href="{{ registry.uri }}objects/info/add/?oid={{ obj.0.id }}">добавить информацию</a>
	</div>
</div>

<div style="margin-left: 100px">

<div style="overflow: hidden; padding-top: 5px">

<span style="padding: 5px 8px" class="button">
<a href="{{ siteName }}{{ registry.uri }}tt/add/?oid={{ obj.0.id }}" title="добавить задачу">
<img border="0" style="position: relative; top: 5px" alt="plus" src="{{ registry.uri }}img/plus-button.png" />
добавить задачу
</a>
</span>

<span style="padding: 5px 8px; margin-left: 5px" class="button">
<a style="cursor: pointer; margin-right: 2px" onclick="getInfo({{ obj.0.id }})">
<img src="{{ registry.uri }}img/information-button.png" title="полные данные" alt="info" border="0" style="position: relative; top: 4px; left: 3px" />
</a>
</span>

<span style="padding: 5px 8px; margin-left: 5px" class="button">
<a style="margin-right: 2px" href="{{ registry.uri }}objects/edit/{{ obj.0.id }}/">
<img src="{{ registry.uri }}img/edititem.gif" title="правка" alt="edit" border="0" style="position: relative; top: 4px; left: 3px" />
</a>
</span>

<span style="padding: 5px 8px; margin-left: 5px" class="button">
<a style="cursor: pointer" onclick="refreshurl('{{ siteName }}{{ registry.uri }}objects/{{ obj.0.id }}/')">
<img src="{{ registry.uri }}img/enter.png" title="перейти к объекту" alt="object" border="0" style="position: relative; top: 4px" />
</a>
</span>

</div>




<div>
{% if obj.0.email %}
<h3><b>email:</b> <a href="mailto: {{ obj.0.email }}">{{ obj.0.email }}</a></h3>
{% endif %}

{% for part in obj %}
{% if part.main %}
<h3><b>{{ part.field }}:</b>&nbsp;{{ part.val }}</h3>
{% endif %}
{% endfor %}
</div>

</div>

</div>

</div>
{% endfor %}

</div>

<script type="text/javascript">
function moveConfirm() {
	$($("#moveGroups")).dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { move(); $(this).dialog("close"); }
		},
		width: 240
	});
}

function move() {
        var objs = $("#objStructure #obj:checked");

        var formData = new Array(); var i = 0;
        $("#objStructure .objs:checkbox:checked").each(function(n){
			id = this.id;
			
			formData[i] = ['"' + id + '"', "1"].join(":");
			
			i++;
        });

        var json = "{" + formData.join(",") + "}";

        var data = "action=move_objs&json=" + json + "&sub=" + $("#selsub").val();
        $.ajax({
        type: "POST",
        async: false,
        url: '{{ registry.uri }}ajax/objects/',
        data: data,
        success: function(res) {
				document.location.href = document.location.href;
			}
        });
}

function writeMail() {
        var objs = $("#objStructure #obj:checked");

        var formData = new Array(); var i = 0;
        $("#objStructure .objs:checkbox:checked").each(function(n){
			id = this.id;
			
			formData[i] = ['"' + id + '"', "1"].join(":");
			
			i++;
        });

        var json = "{" + formData.join(",") + "}";

        $.ajax({
	        type: "POST",
	        async: false,
	        url: '{{ registry.uri }}ajax/mail/',
	        data: "action=writeMail&json=" + json,
	        success: function(res) {
				document.location.href = url + 'mail/compose/?obj';
			}
        });
}
</script>