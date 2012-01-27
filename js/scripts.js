$('#in_find').watermark('Поиск');

if($("ul.dropdown").length) {
	$("ul.dropdown li").dropdown();
};

$.fn.dropdown = function() {

	return this.each(function() {

		$(this).hover(function(){
			$(this).addClass("hover");
			$('> .dir',this).addClass("open");
			$('ul:first',this).css('visibility', 'visible');
		},function(){
			$(this).removeClass("hover");
			$('.open',this).removeClass("open");
			$('ul:first',this).css('visibility', 'hidden');
		});

	});

}

var url;

function otmsInit(path) {
    url = path;
}

function refreshurl(refreshurl) {
	document.location.href = refreshurl;
}

function delava() {
    var data = "action=delAva";
    $.ajax({
            type: "POST",
            url: url + "ajax/profile/",
            data: data,
            success: function(res) {
            	$(".avatar").attr("src", url + "img/noavatar.gif");
            }
    });
}

function loadava() {
    $("#selavatar").dialog({
            modal: true,
            width: 300,
            height: 120
    });
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