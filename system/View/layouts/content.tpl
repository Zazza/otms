<div id="mainContent">

<div id="advinfo" style="display: none; text-align: left" title="Текст">

<div id="showFM" style="cursor: pointer" onclick="showFM()" style="widh: 30px; height: 20px; border: 1px solid #777"><img src="{{ uri }}img/folder--plus.png" alt="" border="0" /> FM</div>
<div id="hideFM" style="cursor: pointer; display: none" onclick="hideFM()" style="widh: 30px; height: 20px; border: 1px solid #777"><img src="{{ uri }}img/folder--minus.png" alt="" border="0" /> FM</div>

<div id="fm" style="float: left"></div>

<div id="text_area" style="float: left">
    <textarea id="jHtmlArea" name="textfield" style="width: 500px; height: 220px"></textarea>
    
    <p style="margin-top: 20px" style="display: none" id="tag">
        Теги: <input type="text" id="tags" style="width: 250px" />
    </p>
</div>

</div>

<div id="findObject" style="display: none; text-align: left" title="Поиск объекта">
<div style="margin-bottom: 20px">
    <input type="text" name="dFind" id="dFind" />
</div>

<div id="resFind"></div>

</div>

<div id="ttgroup" style="display: none; text-align: center" title="Закрыть задачу">
<p>Уверены, что хотите закрыть задачу?</p>
</div>

<div id="leftBlock">
    {{ leftBlock }}
</div>

<div id="rightMainContent">
    {{ main_content }}
</div>

</div>

{% if leftBlock == "" %}
<script type="text/javascript">
$("#menucover").hide();
$("#leftBlock").hide();
$("#rightMainContent").css("margin-left", "0");
</script>
{% endif %}

<script type="text/javascript">
$(document).ready(function(){
    $("#dFind").keypress(function(e) {
        if (e.which == 13) {
            dFindObj();
        }
    })
})

var string = "";

$("#dFind").keyup(function(e) {
    firstFind();
})

function firstFind() {
    string = $("#dFind").val();

    if (string.length > 3) {
    $("#resFind").html('<img src="{{ uri }}img/ajax-loader.gif" alt="ajax-loader.gif" border="0" />');
    
        if(find.timeout) {
            clearTimeout(find.timeout);
        }
        
        find.timeout = setTimeout(find, 1000);
    
    } else {
        $("#resFind").html('');
    }
}

function find(){
    var data = "action=findObj&find=" + $("#dFind").val() + "&page=" + window.location.hash;
    $.ajax({
    	type: "POST",
    	url: "{{ uri }}ajax/tt/",
    	data: data,
    	success: function(res) {
            $("#resFind").html(res);
        }
    })
}
</script>