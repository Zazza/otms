<a href="{{ registry.uri }}dashboard/" class="dir">
<img src="{{ registry.uri }}img/dashboard.png" alt="" border="0" style="vertical-align: middle" />
Dashboard
</a>
<span id="notifspan" class="nonotif"></span>

<ul id="dashajax">

	<li style="height: 30px">
		<span class="button" style="float: right">
		<a style="cursor: pointer" onclick="clearEvents()">
			<b>Очистить</b>
		</a>
		</span>
	</li>
	
	<li>
	<div id="dashajaxlogs"></div>
	</li>
	
</ul>

<input type="hidden" id="settitle" />

<script type="text/javascript">
$("#notifspan").html("&nbsp;");

var height = document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight;
$("#dashajaxlogs").css("max-height", (height - 150));

$(document).ready(function(){
	$("#settitle").val($("title").text());
			
	var service = 0;
	var notify = 0;
	var numChats = 0;
	var rooms = null;
	var numNotify = 0;
	var events = "";
	
	$.each({{ dash }}, function(key, val) {
		switch(key) {
			case "service":
				service = 1
				numNotify = val
				break
			case "notify":
				if (val > 0) {
					notify = 1
					numNotify = val
				}
				break
			case "numChats":
				numChats = val
				break
			case "rooms":
				rendRooms(val);
				break
			case "events":
				events = "<div>" + val + "</div>"
				break
		}
	});

	if (service) {
		$("#notifspan").addClass("servicenotif");
	} else if (notify) {
		$("#notifspan").addClass("newnotif");
	};
	
	$("#dashajaxlogs").html(events);
	$("#numChats").text(numChats);
	$("#notifspan").text(numNotify);
	
	if (notify) {
		$("title").text(numNotify + " новых уведомлений!");
	};

	$("#dashajax").everyTime(20000, function() {
		var data = "action=newevents";
		$.ajax({
			type: "POST",
			url: "{{ registry.uri }}ajax/dashboard/",
			data: data,
			dataType: 'json',
			success: function(res) {
				//$("#leftBlock").append(res + "<br />");	
				
				var service = 0;
				var notify = 0;
				var numChats = 0;
				var rooms = null;
				var events = null;
				var prev = parseInt($("#notifspan").html());
				var numNotify = prev;

				$.each(res, function(key, val) {
					switch(key) {
						case "service":
							service = 1
							numNotify = prev + parseInt(val);
							break
						case "notify":
							if (val > 0) {
								notify = 1
								numNotify = prev + parseInt(val);
								$("title").text(numNotify + " новых уведомлений!");
							}
							break
						case "numChats":
							numChats = val
							break
						case "rooms":
							$("#chat_rooms").html("");
							
							rendRooms(val)
							break
						case "events":
							if (val) {
								$("#dashajaxlogs #emptyEvents").hide();
								$("#dashajaxlogs").prepend(val);
							};
							break
					}
				});

				if (service) {
					$("#notifspan").removeClass("nonotif");
					$("#notifspan").removeClass("newnotif");
					$("#notifspan").addClass("servicenotif");
				} else if (notify) {
					if (prev == 0) {
						$("#notifspan").addClass("newnotif");
					}
				};

				$("#numChats").text(numChats);
				$("#notifspan").text(numNotify);
			}
		});
	});
});

function rendRooms(val) {
	$.each(val, function(id, room) {
		$("#chat_rooms").append(room);
	});
}

function clearEvents() {
	$('<div class="dashClearDialog"><img src="{{ registry.uri }}img/ajaxCheckMail.gif" alt="ajax-loader.gif" border="0" /></div>').dialog({ modal: true, width: 50, height: 80 });
	
	var data = "action=clearEvents";
	$.ajax({
		type: "POST",
		url: "{{ registry.uri }}ajax/dashboard/",
		data: data,
		success: function(res) {
			$("#dashajaxlogs").html('');
			$("#notifspan").text('0');
			$("#notifspan").removeClass("newnotif");
			$("#notifspan").removeClass("servicenotif");
			$("#notifspan").addClass("nonotif");
			$("title").text('');
			
			$(".dashClearDialog").dialog("close");
		}
	});
}
</script>