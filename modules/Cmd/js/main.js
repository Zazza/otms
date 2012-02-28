$(document).ready(function(){

	$(document).keyup(function(e) {
		switch(e.keyCode) {
			case 192: shConsole(); break;
		};
	});
	
	
	$("#shCmd").click(function() {
		shConsole();
	});
	
	$("#mcmd").keyup(function(e) {
		switch(e.keyCode) {
			case 13: addCmd(); break;
			case 38: $("#mcmd").val($("#prevcmd").val()); break;
			case 40: $("#mcmd").val(""); break;
		};
	});
});

function shConsole() {
	if ($(".semiopacity").css("display") == "none") {
		$(".semiopacity").show();
		$("#mcmd").focus();
	} else {
		var str = $("#mcmd").val();
		$("#mcmd").val(str.substr(0, str.length-1));
		$(".semiopacity").hide();
	}
};

function addCmd() {
	var cmd = $("#mcmd").val();
	$("#prevcmd").val(cmd);
	
	if (cmd == "clear") {
		$(".text").html("");
		$("#mcmd").val("");
	} else {
		var data = "action=addCmd&&message=" + cmd;
		$.ajax({
			type: "POST",
			url: url + "ajax/cmd/",
			data: data,
			success: function(res) {
				$(".text").prepend("<p class='resCmd'>" + res + "</p>");
				$("#mcmd").val("");
			}
		});
	};
};
