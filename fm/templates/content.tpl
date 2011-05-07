<div style="height: 13px; border: 1px solid #DEDEDE; font-size: 11px; font-weight: bold; margin: 0 0 10px 0; padding: 4px; text-align: left">
<div style="float: left"><span id="fm_sel">+</span>/<span id="fm_unsel">-</span></div>
<div style="float: right"><span style="font-size: 10px; font-weight: bold"><a onclick="empty()" style="cursor: pointer; text-decoration: underline">очистить лог загрузки</a></span></div>
</div>

<p style="font-size: 11px; font-weight: bold; padding: 0; margin: 4px 0 10px 0">{{ shPath }}</p>

<div style="overflow: hidden">

{% for part in dirs %}
{% if part.name != ".." %}
<div id="fm_dirs">
<div style="float: left; cursor: pointer; margin-right: 20px; background: url('{{ url }}/img/ftypes/folder.png') no-repeat; padding: 3px 0 3px 18px" onclick="chdir('{{ part.name }}')">[{{ part.name }}]</div>
<img src="{{ url }}/img/delete.png" style="float: right; cursor: pointer" onclick="rmDirDialog('{{ part.name }}')" />
<div style="color: #777; clear: both"><span style="float: left">{{ part.date }}</span><span style="float: right">[ПАПКА]</span></div>
</div>
{% else %}
<div id="fm_up">
<img src="{{ url }}/img/back.png" style="float: left; cursor: pointer" onclick="chdir('{{ part.name }}')" />
<div style="cursor: pointer; margin-left: 20px; font-size: 12px" onclick="chdir('{{ part.name }}')">[{{ part.name }}]</div>
</div>
{% endif %}
{% endfor %}

<div id="fm_uploadDir">
{% set i = 0 %}
{% for part in files %}
{% set i = i + 1 %}
<div id="fm_file{{ i }}" class="fm_unsellabel">
{% if part.ext == "jpg" or part.ext == "jpeg" or part.ext == "png" or part.ext == "gif" %}
<a class="fm_pre" name="{{ part.name }}" id="fm_filename{{ i }}" style="cursor: pointer; background: url('{{ url }}//img/ftypes/img.png') no-repeat; padding: 3px 0 3px 18px">{{ part.name }}</a>
{% elseif part.ext == "xml" %}
<a class="fm_pre" name="{{ part.name }}" id="fm_filename{{ i }}" style="cursor: pointer; background: url('{{ url }}//img/ftypes/xml.png') no-repeat; padding: 3px 0 3px 18px">{{ part.name }}</a>
{% else %}
<a class="fm_pre" name="{{ part.name }}" id="fm_filename{{ i }}" style="cursor: pointer; background: url('{{ url }}//img/ftypes/unknown.png') no-repeat; padding: 3px 0 3px 18px">{{ part.name }}</a>
{% endif %}
<div style="color: #777; clear: both"><span style="float: left">{{ part.date }}</span><span style="float: right">[{{ part.size }}]</span></div>
</div>
{% else %}
<div id="fm_empty" style="text-align: center; width: 100%">пусто</div>
{% endfor %}
</div>

</div>

<p style="padding: 0; margin: 4px 0"><b>Итого:</b> <span id="fm_total">{{ totalsize }}</span></p>

<p style="padding: 0; margin: 4px 0">С выделенными:&nbsp;<a onclick="delmany()" style="cursor: pointer; float: right; background: url('{{ url }}/img/delete.png') no-repeat; padding: 1px 0 0 18px">Удалить</a></p>

<div id="fm_move">
<div style="float: left; margin-right: 2px">
<select id="fm_moveToDir">
{% for part in subdirs %}
<option value="{{ part.name }}">{{ part.name }}</option>
{% endfor %}
</select>
</div>
<a onclick="moveFiles()" style="cursor: pointer; float: right">
    <input type="button" value="Переместить" style="font-size: 12px" />
</a>
</div>

<div id="fm_createDir">
<div style="float: left; margin-right: 5px">
<input type="text" name="dirname" id="fm_dirname" />
</div>
<a onclick="createDir()" style="cursor: pointer; float: right">
    <input type="button" value="Создать" style="font-size: 12px" />
</a>
</div>

<input name="lastIdRow" id="fm_lastIdRow" value="{{ i }}" type="hidden" />
<input name="max" id="fm_max" value="{{ i }}" type="hidden" />

{{ javascript }}
<script type="text/javascript">
$('#fm_dirname').watermark('Имя папки');

xOffset = -15;
yOffset = 15;

setDrag();

pre();

function pre() {
    $("a.fm_pre").hover(function(e){
        var fname = this.name;
        var ext = fname.substr(fname.lastIndexOf(".")+1, fname.length-fname.lastIndexOf(".")-1);
        ext = ext.toLowerCase();
        
        if ( (ext == "gif") || (ext == "png") || (ext == "jpg") || (ext == "jpeg") ) {
            
    	$("body").append("<p id='fm_preview_t'><img src='{{ url }}/../../{{ _thumb }}" + this.name + "' alt='просмотр изображения' id='fm_t_img_pre' /></p>");
    	
    	var img = new Image();
    	img.src = "{{ _thumb }}" + this.name;
    
    	$("#fm_preview_t")
    		.css("top",(e.pageY - xOffset) + "px")
    		.css("left",(e.pageX - yOffset) + "px")
    		.css("border", "0px")
    		.fadeIn("fast");
      }
    },
    function(){
    	$("#fm_preview_t").remove();
    });	
    
    $("a.fm_pre").mousemove(function(e){
        var fname = this.name;
        var ext = fname.substr(fname.lastIndexOf(".")+1, fname.length-fname.lastIndexOf(".")-1);
        ext = ext.toLowerCase();
        
        if ( (ext == "gif") || (ext == "png") || (ext == "jpg") || (ext == "jpeg") ) {
        	var img = new Image();
        	img.src = "{{ _thumb }}" + this.name;
        	
        	$("#fm_preview_t")
        		.css("border", "0px")
        		.css("top",(e.pageY - xOffset) + "px")
        		.css("left",(e.pageX - yOffset) + "px");
        }
    });
};

$("#fm_sel").click(function() {
    $(".fm_unsellabel").removeClass("fm_unsellabel").addClass("fm_sellabel");
});

$("#fm_unsel").click(function() {
    $(".fm_sellabel").removeClass("fm_sellabel").addClass("fm_unsellabel");
});

$(".fm_unsellabel").live("click", function(){
    $(this).removeClass("fm_unsellabel").addClass("fm_sellabel");
});

$(".fm_sellabel").live("click", function(){
    $(this).removeClass("fm_sellabel").addClass("fm_unsellabel");
});

function createDir() {
    var _dirName = $("#fm_dirname").val();
    if (_dirName.length == 0) {
    	$('<div title="Уведомление">Имя папки не задано!</div>').dialog({
    		modal: true,
    	    buttons: {
                "Закрыть": function() { $(this).dialog("close"); }
    		}
    	});
    } else {
        $.ajax({
        	type: "POST",
        	url: '{{ url }}/index.php?main=ajax',
        	data: "action=createDir&dirName=" + _dirName,
        	success: function(res) {
        		$("#fm_filesystem").html(res);
        	}
        })
    }
};

function rmDirDialog(dirName) {
	$('<div title="Уведомление">Вы действительно хотите удалить директорию <b>' + dirName + '</b>?</div>').dialog({
		modal: true,
	    buttons: {
            "Нет": function() { $(this).dialog("close"); },
			"Да": function() { rmDir(dirName); $(this).dialog("close"); }
		}
	});
}

function rmDir(dirName) {
    $.ajax({
    	type: "POST",
    	url: '{{ url }}/index.php?main=ajax',
    	data: "action=rmDir&dirName=" + dirName,
    	success: function(res) {
            $("#fm_filesystem").html(res);
    	}
    })
};

function moveFiles() {
    var selfiles = "";
    for (i = 1; i <= parseInt($("#fm_max").val()); i++){
        if ($("#fm_file" + i).attr("class") == "fm_sellabel") {
            selfiles += "&file[]" + "=" + $("#fm_filename" + i + "").attr("name");
        }
    };
    
    if (selfiles.length == 0) {
    	$('<div title="Уведомление">Необходимо выбрать файлы!</div>').dialog({
    		modal: true,
    	    buttons: {
                "Закрыть": function() { $(this).dialog("close"); }
    		}
    	});
    } else {    
        $.ajax({
        	type: "POST",
        	url: '{{ url }}/index.php?main=ajax',
        	data: "action=moveFiles&dirName=" + $("#fm_moveToDir").val() + "&" + selfiles,
        	success: function(res) {
                $("#fm_filesystem").html(res);
        	}
        });
    }
}

function empty() {
    $(".qq-upload-list").text("");
}
</script>