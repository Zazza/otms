$(document).ready(function(){
    $("#kbl #structure").treeview({
		persist: "location",
		collapsed: true
    });

    $("#kbl #ajax-load").hide();
    $("#kbl #structure").show();
});

function showInfo(id) {
    var data = "action=getInfo&id=" + id;
	$.ajax({
		type: "POST",
		url: url + "ajax/kb/",
		data: data,
		success: function(res) {
			$('<div title="Информация" style="text-align: left">' + res + '</div>').dialog({ width: 700 });
		}
	});
}

function delAdvConfirm(oaid) {
    $('<div title="Удаление записи">Удалить?</div>').dialog({
            modal: true,
        buttons: {
                    "Нет": function() { $(this).dialog("close"); },
                    "Да": function() { delAdv(oaid); $(this).dialog("close"); }
            },
            width: 240
    });
}

function delAdv(oaid) {
    var data = "action=delAdv&id=" + oaid;
        $.ajax({
                type: "POST",
                url: url + "ajax/tt/",
                data: data,
                success: function(res) {
            document.location.href = document.location.href;
                }
        });
}