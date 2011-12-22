<div style="overflow: hidden; background-color: #EEE; margin: 10px 0 0 0; padding: 4px 10px">

<div style='padding-left: 20px; float: left' class='title'>
{{ date }}
</div>

<div class="blockbd" style="padding-left: 50px; font-size: 11px; float: right">
<p><label><input type="radio" name="caltask" value="0" class="caltask" {% if caltype == 0 %}checked="checked"{% endif %} /><span style="position: relative; bottom: 3px">задачи, где я <b>ответственный</b></span></label></p>
<p><label><input type="radio" name="caltask" value="1" class="caltask" {% if caltype == 1 %}checked="checked"{% endif %} /><span style="position: relative; bottom: 3px">задачи, где я <b>автор</b></span></label></p>
</div>

</div>

<script type="text/javascript">
$(".caltask").click(function() {
	var data = "action=setCalTask&caltask=" + $(this).val();
	$.ajax({
		type: "POST",
		url: "{{ registry.uri }}ajax/tt/",
		data: data,
		success: function(res) {
			document.location.href = document.location.href;
		}
	});
});
</script>