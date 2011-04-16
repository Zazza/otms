$(document).ready(function(){
    $("#fm").hide();
    
    $("#text_area").css("margin-left", "150px");
});

function showFM() {
    $("#fm").show();
    $("#text_area").css("margin-left", "0");
    
    $("#hideFM").show();
    $("#showFM").hide();
}

function hideFM() {
    $("#fm").hide();
    $("#text_area").css("margin-left", "150px");
    
    $("#hideFM").hide();
    $("#showFM").show();
}

function delGroupConfirm(gid) {
	$('<div title="Удаление группы">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delGroup(gid); $(this).dialog("close"); }
		},
		width: 240
	})
}

function delGroup(gid) {
    var data = "action=delGroup&gid=" + gid;
	$.ajax({
		type: "POST",
		url: "/ajax/users/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	})
}

function delUserConfirm(uid) {
	$('<div title="Удаление пользователя">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delUser(uid); $(this).dialog("close"); }
		},
		width: 240
	})
}

function delUser(uid) {
    var data = "action=delUser&uid=" + uid;
	$.ajax({
		type: "POST",
		url: "/ajax/users/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	})
}

function delGroupTtConfirm(id) {
	$('<div title="Удаление группы">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delGroupTt(id); $(this).dialog("close"); }
		},
		width: 240
	})
}

function delGroupTt(id) {
    var data = "action=delGroup&id=" + id;
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	})
}

function delGroupKbConfirm(id) {
	$('<div title="Удаление группы">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delGroupKb(id); $(this).dialog("close"); }
		},
		width: 240
	})
}

function delGroupKb(id) {
    var data = "action=delGroup&id=" + id;
	$.ajax({
		type: "POST",
		url: "/ajax/kb/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	})
}

function delTemplateConfirm(id) {
	$('<div title="Удаление шаблона">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delTemplate(id); $(this).dialog("close"); }
		},
		width: 240
	})
}

function delTemplate(id) {
    var data = "action=delTemplate&id=" + id;
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	})
}

function getInfo(id) {
    var data = "action=getInfo&id=" + id;
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
       	   $('<div title="Информация">' + res + '</div>').dialog();
		}
	})
}

function showEditTask(tid) {
    init('/fm/');

    $("#jHtmlArea").text("");
    $("#jHtmlArea").htmlarea();

    $('#advinfo').dialog({
		modal: true,
        close: $("#jHtmlArea").htmlarea("dispose"),
	    buttons: {
			"Добавить": function() {
                var data = "action=addComment&tid=" + tid + "&text=" + $("#jHtmlArea").htmlarea('toHtmlString');
            	$.ajax({
            		type: "POST",
            		url: "/ajax/tt/",
            		data: data,
            		success: function(res) {
                        document.location.href = "/tt/" + tid + "/";
            		}
            	})
            }
		},
		width: 800,
        height: 460
	});
    
    $("#jHtmlArea").htmlarea();
}

function showTaskWindow() {
    init('/fm/');

    $("#jHtmlArea").val($("#tasktext").html());

    $('#advinfo').dialog({
		modal: true,
        close: $("#jHtmlArea").htmlarea("dispose"),
	    buttons: {
			"Добавить": function() {
			     $("#tasktext").show();
                 $("#tasktext").html($("#jHtmlArea").htmlarea('toHtmlString'));
			     $("#task").html($("#jHtmlArea").htmlarea('toHtmlString'));
                 $(this).dialog("close");
            }
		},
		width: 800,
        height: 460
	});
    
    $("#jHtmlArea").htmlarea();
}

function showAdvanced(id) {
    init('/fm/');

    $('#advinfo').dialog({
		modal: true,
        close: $("#jHtmlArea").htmlarea("dispose"),
	    buttons: {
			"Добавить": function() {
                addAdvanced(id, encodeURIComponent($("#jHtmlArea").htmlarea('toHtmlString')));
            }
		},
		width: 800,
        height: 460
	});
    
    $("#jHtmlArea").htmlarea();
}

function addAdvanced(id, text) {
    var data = "action=addAdvanced&id=" + id + "&text=" + text;
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = "/objects/" + id + "/";
		}
	})
}

function editAdv(id, oaid) {
    init('/fm/');

    $('#advinfo').dialog({
		modal: true,
        close: $("#jHtmlArea").htmlarea("dispose"),
	    buttons: {
			"Изменить": function() {
			     editAdvanced(id, oaid, encodeURIComponent($("#jHtmlArea").htmlarea('toHtmlString')));
                 $(this).dialog("close");
            }
		},
		width: 800,
        height: 460
	});
    
    var data = "action=getAdvanced&id=" + oaid;
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
            $("#jHtmlArea").text(res);
            $("#jHtmlArea").htmlarea();
		}
	})
}

function editAdvanced(id, oaid, text) {
    var data = "action=editAdvanced&id=" + oaid + "&text=" + text;
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = "/objects/" + id + "/";
		}
	})
}

function delAdvConfirm(id, oaid) {
	$('<div title="Удаление записи">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delAdv(id, oaid); $(this).dialog("close"); }
		},
		width: 240
	})
}

function delAdv(id, oaid) {
    var data = "action=delAdv&id=" + oaid;
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = "/objects/" + id + "/";
		}
	})
}

function showAdv(win) {
    var adv = win + " .adv:not(:first)";
    
    $(adv).show();
    
    var sall = win + " #showall";
    $(sall).hide();
    var hall = win + " #hideall";
    $(hall).show();
}

function hideAdv(win) {
    var adv = win + " .adv:not(:first)";
    
    $(adv).hide();
    
    var sall = win + " #showall";
    $(sall).show();
    var hall = win + " #hideall";
    $(hall).hide();
}

function selObject() {
	$('#findObject').dialog({ width: 400, height: 500 });
}

function selObj(data) {
    $("#selObj").html("");
     $.each(data, function(key, val) {
        if (key == "id") {
            $("#findObject").dialog("close");
            
            $("#newObj").show();
            $("#selObjHid").val(val);
        } else {
            $("#selObj").append("<p><b>" + key + "</b>: " + val + "</p>");
        }
     })
}

function dFindObj() {
    var data = "action=findObj&find=" + $("#dFind").val();
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
            $("#resFind").html(res);
		}
	})
}

function closeTask(tid) {
    $('#ttgroup').dialog({
		modal: true,
        close: $("#jHtmlArea").htmlarea("dispose"),
	    buttons: {
			"Готово": function() {
			     moveTask(tid, $("#ttgid").val());
                 $(this).dialog("close");
            }
		},
		width: 200,
        height: 140
	});
}

function moveTask(tid, gid) {
    var data = "action=closeTask&tid=" + tid + "&gid=" + gid;
	$.ajax({
		type: "POST",
		url: "/ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = "/tt/";
		}
	})
}

function spam(tid) {
    var data = "action=spam&tid=" + tid;
	$.ajax({
		type: "POST",
		url: "/ajax/users/",
		data: data,
		success: function(res) {
            document.location.href = "/tt/" + tid + "/";
		}
	})
}