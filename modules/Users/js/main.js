$(document).ready(function(){
	$("#utree > #Pstructure").treeview({
		persist: "location",
		collapsed: true
    });

	$('#Prall').click(function(){
		if ($("#Prall").attr("checked")) {
			$('#Pstructure input:checkbox:enabled').each(function(){this.disabled = !this.disabled});
			$('#Pstructure input:checkbox').removeAttr("checked");
		} else {
			$('#Pstructure input:checkbox:disabled').each(function(){this.disabled = !this.disabled});
			$('#Pstructure input:checkbox').removeAttr("checked");
		}
		
		$('#Prall').removeAttr("disabled");
	});

	$('.Pgruser').click(function(){
		$('input.Pg' + $(this).val() + ':checkbox').each(function(){this.disabled = !this.disabled});
		$('#Prall').removeAttr("checked");
	});

	$('.Pcusers').click(function(){
		$('#Prall').removeAttr("checked");
	});
});


function delUserConfirm(uid) {
	$('<div title="Удаление пользователя">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delUser(uid); $(this).dialog("close"); }
		},
		width: 240
	});
}

function delUser(uid) {
    var data = "action=delUser&uid=" + uid;
	$.ajax({
		type: "POST",
		url: url + "ajax/users/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	});
}

function getUserInfo(uid) {
    var data = "action=getUI&uid=" + uid;
    $.ajax({
            type: "POST",
            url: url + "ajax/users/",
            data: data,
            success: function(res) {
    			$('<div title="Информация" style="text-align: left">' + res + '</div>').dialog({ width: 500 });
            }
    });
}
