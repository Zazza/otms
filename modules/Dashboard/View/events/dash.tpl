<div class="dashEvent" id="ev{{ event.id }}">

<div style="overflow: hidden">
	<div class="dashEventSub">
		{{ event.timestamp }}
		<div onclick="closeDashEvent({{ event.id }})" style="cursor: pointer; float: right; color: red">закрыть</div>
	</div>
	<div style="float: left; padding: 1px 3px; margin-right: 10px; font-weight: bold; color: green">{{ event.event }}</div>
</div>

<span>
{% for part in event.param %}
{% if part.key %} 
<p><b>{{ part.key }}:</b> {{ part.val }}</p>
{% endif %}
{% endfor %}
</span>

</div>

<script type="text/javascript">
function closeDashEvent(id) {
	var data = "action=closeEvent&eid=" + id;
	$.ajax({
		type: "POST",
		url: "{{ registry.uri }}ajax/dashboard/",
		data: data,
		success: function(res) {
			$("#ev" + id).fadeOut("fast");
			
			var num = $("#notifspan").html() - 1;
			
			if (num == 0) {
				$("#notifspan").removeClass("servicenotif");
				$("#notifspan").removeClass("newnotif");
				$("#notifspan").addClass("nonotif");
				
				$("#dashajaxlogs").html("<p id='emptyEvents'>Новых событий нет</p>");
			} else if (!res) {
				$("#notifspan").removeClass("servicenotif");
				$("#notifspan").addClass("newnotif");
			}
			
			$("#notifspan").html(num);
			$("title").text($("#settitle").val());
		}
	});	
}
</script>