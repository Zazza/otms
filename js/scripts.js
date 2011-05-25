$(document).ready(function(){
    $("#fm").hide();
    
    $("#text_area").css("margin-left", "150px");
    
    $("#structure").treeview({
		persist: "location",
		collapsed: true
    });
});

var url;

function otmsInit(path) {
    url = path;
}

function htmlarea() {
    $("#jHtmlArea").htmlarea({
        toolbar: [
            ["bold", "italic", "underline", "|", "forecolor"],
            ["p", "h1", "h2", "h3", "h4", "h5", "h6"],
            ["link", "unlink", "|", "image"]
            ]});
}

function clearHtmlArea() {
    $("#jHtmlArea").text("");
    $("#jHtmlArea").htmlarea("dispose");
}

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
		url: url + "ajax/users/",
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
		url: url + "ajax/users/",
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
		url: url + "ajax/tt/",
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
		url: url + "ajax/tt/",
		data: data,
		success: function(res) {
       	   $('<div title="Информация">' + res + '</div>').dialog();
		}
	})
}

function showEditTask(tid) {
    fminit(url + 'fm/');

    $('#advinfo #tag').hide();

    $('#advinfo').dialog({
		modal: true,
        close: clearHtmlArea(),
	    buttons: {
			"Добавить": function() {
                var data = "action=addComment&tid=" + tid + "&text=" + $("#jHtmlArea").htmlarea('toHtmlString');
            	$.ajax({
            		type: "POST",
            		url: url + "ajax/tt/",
            		data: data,
            		success: function(res) {
                        document.location.href = url + "tt/" + tid + "/";
            		}
            	})
            }
		},
		width: 700,
        height: 490
	});
    
    htmlarea();   
}

function showTaskWindow() {
    fminit(url + 'fm/');

    $('#advinfo #tag').hide();

    $('#advinfo').dialog({
		modal: true,
	    buttons: {
			"Добавить": function() {
			     $("#tasktext").show();
                 $("#tasktext").html($("#jHtmlArea").htmlarea('toHtmlString'));
			     $("#task").text($("#jHtmlArea").htmlarea('toHtmlString'));
                 $(this).dialog("close");
            }
		},
		width: 800,
        height: 450
	});
    
    $("#jHtmlArea").val($("#tasktext").html());
    htmlarea();
}

function showAdvanced(id) {
    fminit(url + 'fm/');
    
    clearHtmlArea();
    
    $('#advinfo #tag').show();

    $('#advinfo').dialog({
		modal: true,
        close: clearHtmlArea(),
	    buttons: {
			"Добавить": function() {
                addAdvanced(id, encodeURIComponent($("#jHtmlArea").htmlarea('toHtmlString')), $("#tags").val());
            }
		},
		width: 900,
        height: 570
	});
    
    htmlarea();
}

function addAdvanced(id, text, tags) {
    var data = "action=addAdvanced&id=" + id + "&text=" + text + "&tags=" + tags;
	$.ajax({
		type: "POST",
		url: url + "ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = url + "objects/" + id + "/";
		}
	})
}

function editAdv(id, oaid) {
    fminit(url + 'fm/');         
    
    $('#advinfo #tag').show();

    $('#advinfo').dialog({
		modal: true,
        close: clearHtmlArea(),
	    buttons: {
			"Изменить": function() {
			     editAdvanced(id, oaid, encodeURIComponent($("#jHtmlArea").htmlarea('toHtmlString')), $("#tags").val());
                 $(this).dialog("close");
            }
		},
		width: 900,
        height: 570
	});
    
    var data = "action=getAdvanced&id=" + oaid;
	$.ajax({
		type: "POST",
		url: url + "ajax/tt/",
		data: data,
        dataType: 'json',
		success: function(res) {
            $.each(res, function(key, val) {
                if (key == "adv") {
                    $("#jHtmlArea").text(val);
                    htmlarea();
                } else if (key == "tags") {
                    $("#tags").val(val);
                }
            })
		}
	})
}

function editAdvanced(id, oaid, text, tags) {
    var data = "action=editAdvanced&id=" + oaid + "&text=" + text + "&tags=" + tags;
	$.ajax({
		type: "POST",
		url: url + "ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = url + "objects/" + id + "/";
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
		url: url + "ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = url + "objects/" + id + "/";
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
		url: url + "ajax/tt/",
		data: data,
		success: function(res) {
            $("#resFind").html(res);
		}
	})
}

function closeTask(tid) {
    $('#ttgroup').dialog({
		modal: true,
	    buttons: {
			"Да": function() {
			     moveTask(tid);
                 $(this).dialog("close");
            },
            "Нет": function() { $(this).dialog("close"); }
		},
		width: 200,
        height: 150
	});
}

function moveTask(tid) {
    var data = "action=closeTask&tid=" + tid;
	$.ajax({
		type: "POST",
		url: url + "ajax/tt/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	})
}

function spam(tid) {
    var data = "action=spam&tid=" + tid;
	$.ajax({
		type: "POST",
		url: url + "ajax/users/",
		data: data,
		success: function(res) {
            document.location.href = url + "tt/" + tid + "/";
		}
	})
}

function addruser() {
    var data = "action=getUser&id=" + $("#ruser").val() + "&type=" + $("#ruser").find("option:selected").attr("title");
	$.ajax({
		type: "POST",
		url: url + "ajax/users/",
		data: data,
		success: function(res) {
            $("#addedusers").append(res);
		}
	})
}