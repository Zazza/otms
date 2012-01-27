<p><b>Меню:</b></p>
<ul id="temp_menu" style="overflow: hidden; margin: 8px 12px"></ul>
<input type="button" id="saveMenu" value="Сохранить" />
<input type="button" id="clearMenu" value="Сбросить" style="color: red" />

<p><b>Панель быстрого запуска:</b></p>
<ul id="temp_fastmenu" style="overflow: hidden; margin: 8px 12px"></ul>
<input type="button" id="saveFastmenu" value="Сохранить" />
<input type="button" id="clearFastmenu" value="Сбросить" style="color: red" />

<script type="text/javascript">

$("#topotms > ul.dropdown > li > a").each(function(n){
	if (this.innerHTML) {
		$("#temp_menu").append("<li class='tempmenu'>" + this.innerHTML + '</li>');
	}
});

$("#temp_menu").sortable();

$("#saveMenu").click(function() {
	var i = 0; var temp_menu = new Array();
	$("#temp_menu li").each(function(n){
		temp_menu[i] = ['"' + i + '"', '"' + this.innerHTML + '"'].join(":");
		
		i++;
	});
	
	var json = "{" + temp_menu.join(",") + "}";
	
	var data = "action=saveMenu&json=" + json;
	$.ajax({
		type: "POST",
    	url: "{{ registry.uri }}ajax/settings/",
    	data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	});
});

$(".downsh > ul.dropdown > li.topmenubutton > a:visible").each(function(n){
	if (this.innerHTML) {
		$("#temp_fastmenu").append("<li class='tempmenu'>" + this.innerHTML + '</li>');
	}
});

$("#temp_fastmenu").sortable();

$("#saveFastmenu").click(function() {
	var i = 0; var temp_fastmenu = new Array();
	$("#temp_fastmenu li").each(function(n){
		temp_fastmenu[i] = ['"' + i + '"', '"' + this.innerHTML + '"'].join(":");
		
		i++;
	});
	
	var json = "{" + temp_fastmenu.join(",") + "}";
	
	var data = "action=saveFastmenu&json=" + json;
	$.ajax({
		type: "POST",
    	url: "{{ registry.uri }}ajax/settings/",
    	data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	});
});

$("#clearMenu").click(function() {
	var data = "action=clearMenu";
	$.ajax({
		type: "POST",
    	url: "{{ registry.uri }}ajax/settings/",
    	data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	});
});

$("#clearFastmenu").click(function() {
	var data = "action=clearFastmenu";
	$.ajax({
		type: "POST",
    	url: "{{ registry.uri }}ajax/settings/",
    	data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	});
});
</script>