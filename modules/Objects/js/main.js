$(document).ready(function(){
    $("#objl #structure").treeview({
		persist: "location",
		collapsed: true
    });

    $("#objl #ajax-load").hide();
    $("#objl #structure").show();
});

function delTemplateConfirm(id) {
	$('<div title="Удаление шаблона">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delTemplate(id); $(this).dialog("close"); }
		},
		width: 240
	});
}

function delTemplate(id) {
    var data = "action=delTemplate&id=" + id;
	$.ajax({
		type: "POST",
		url: url + "ajax/objects/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	});
}

function getInfo(id) {
    var data = "action=getInfo&id=" + id;
	$.ajax({
		type: "POST",
		url: url + "ajax/objects/",
		data: data,
		success: function(res) {
       	   $('<div title="Информация">' + res + '</div>').dialog({ width: 600 });
		}
	});
}


function delFormConfirm(id) {
	$('<div title="Удаление формы">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delForm(id); $(this).dialog("close"); }
		},
		width: 280
	});
}

function delForm(id) {
    var data = "action=delForm&id=" + id;
    $.ajax({
            type: "POST",
            url: url + "ajax/ai/",
            data: data,
            success: function(res) {
    			document.location.href = url + 'objects/forms/';
            }
    });
}