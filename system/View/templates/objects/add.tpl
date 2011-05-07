<p><b>Новый объект</b></p>

<div id="fields"></div>

<script type="text/javascript">
var data = "action=getTemplateFields&id={{ pname }}";
$.ajax({
	type: "POST",
	url: "{{ uri }}ajax/tt/",
	data: data,
	success: function(res) {
        $("#fields").html(res);
	}
})
</script>