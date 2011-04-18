<div class="blockbd" style="width: 350px; float: right">

<p>
<span>
<select id="month" name="month">
    <option value="01"{% if month == 1 %}selected="selected"{% endif %}>января</option>
    <option value="02"{% if month == 2 %}selected="selected"{% endif %}>февраля</option>
    <option value="03"{% if month == 3 %}selected="selected"{% endif %}>марта</option>
    <option value="04"{% if month == 4 %}selected="selected"{% endif %}>апреля</option>
    <option value="05"{% if month == 5 %}selected="selected"{% endif %}>мая</option>
    <option value="06"{% if month == 6 %}selected="selected"{% endif %}>июня</option>
    <option value="07"{% if month == 7 %}selected="selected"{% endif %}>июля</option>
    <option value="08"{% if month == 8 %}selected="selected"{% endif %}>августа</option>
    <option value="09"{% if month == 9 %}selected="selected"{% endif %}>сентября</option>
    <option value="10"{% if month == 10 %}selected="selected"{% endif %}>октября</option>
    <option value="11"{% if month == 11 %}selected="selected"{% endif %}>ноября</option>
    <option value="12"{% if month == 12 %}selected="selected"{% endif %}>декабря</option>
</select>
</span>
<span>
<select name="year" id="year">
    {% for part in calYear %}
    <option value="{{ part }}">{{ part }}</option>
    {% endfor %}
</select>
</span>

<input type="button" id="seldate" name="seldate" value="Сменить" onclick="getMonth()" />
</p>

</div>

<div class="blockbd" style="width: 400px; font-size: 11px">

<p><b>Задачи:</b></p>

<div style="margin-bottom: 10px">
<a style="cursor: pointer" href="/tt/?task=allmy">Всего моих задач:</a>
&nbsp;<b>[{{ allmytask }}]</b>
</div>

<span style="position: static; margin-left: 10px">
<a title="периодические" href="/tt/?task=iter"><img style="position: relative; top: 3px" src="/img/calendar-blue.png" alt="" border="0" /></a> <b>[{{ itertask }}]</b>
</span>

<span style="position: static; margin-left: 10px">
<a title="ограниченные по времени" href="/tt/?task=time"><img src="/img/alarm-clock.png" alt="" border="0" style="vertical-align: middle" /></a> <b>[{{ timetask }}]</b>
</span>

{% set noiter = allmytask - itertask - timetask %}
<span style="float: right; position: static; margin-right: 10px">
<a title="неограниченные по времени" href="/tt/?task=noiter"><img src="/img/clock.png" alt="" border="0" style="vertical-align: middle" /></a> <a href="/tt/?task=noiter">Глобальные</a> <b>[{{ noiter }}]</b>
</span>

<div style="margin-top: 10px">
<a style="cursor: pointer" href="/tt/?task=me">Созданных мною</a> <b>[{{ allmetask }}]</b></p>
</div>

<div style="margin-top: 10px">
<img src="/img/flag.png" alt="" /> - закрытые задачи
</div>

</div>

<table cellpadding="3" cellspacing="2" width="100%">

<tr style="height: 30px">
<td width="14%" style="border: 1px solid #CCC; text-align: center"><b>Понедельник</b></td>
<td width="14%" style="border: 1px solid #CCC; text-align: center"><b>Вторник</b></td>
<td width="14%" style="border: 1px solid #CCC; text-align: center"><b>Среда</b></td>
<td width="14%" style="border: 1px solid #CCC; text-align: center"><b>Четверг</b></td>
<td width="14%" style="border: 1px solid #CCC; text-align: center"><b>Пятница</b></td>
<td width="14%" style="border: 1px solid #CCC; background-color: #F7F7F1; text-align: center"><b>Суббота</b></td>
<td width="14%" style="border: 1px solid #CCC; background-color: #F7F7F1; text-align: center"><b>Воскресенье</b></td>
</tr>

<tr style="height: 100px">
<td id="11" class="caltd"></td>
<td id="12" class="caltd"></td>
<td id="13" class="caltd"></td>
<td id="14" class="caltd"></td>
<td id="15" class="caltd"></td>
<td id="16" class="caltd"></td>
<td id="17" class="caltd"></td>
</tr>

<tr style="height: 100px">
<td id="21" class="caltd"></td>
<td id="22" class="caltd"></td>
<td id="23" class="caltd"></td>
<td id="24" class="caltd"></td>
<td id="25" class="caltd"></td>
<td id="26" class="caltd"></td>
<td id="27" class="caltd"></td>
</tr>

<tr style="height: 100px">
<td id="31" class="caltd"></td>
<td id="32" class="caltd"></td>
<td id="33" class="caltd"></td>
<td id="34" class="caltd"></td>
<td id="35" class="caltd"></td>
<td id="36" class="caltd"></td>
<td id="37" class="caltd"></td>
</tr>

<tr style="height: 100px">
<td id="41" class="caltd"></td>
<td id="42" class="caltd"></td>
<td id="43" class="caltd"></td>
<td id="44" class="caltd"></td>
<td id="45" class="caltd"></td>
<td id="46" class="caltd"></td>
<td id="47" class="caltd"></td>
</tr>

<tr style="height: 100px">
<td id="51" class="caltd"></td>
<td id="52" class="caltd"></td>
<td id="53" class="caltd"></td>
<td id="54" class="caltd"></td>
<td id="55" class="caltd"></td>
<td id="56" class="caltd"></td>
<td id="57" class="caltd"></td>
</tr>

<tr style="height: 100px">
<td id="61" class="caltd"></td>
<td id="62" class="caltd"></td>
<td id="63" class="caltd"></td>
<td id="64" class="caltd"></td>
<td id="65" class="caltd"></td>
<td id="66" class="caltd"></td>
<td id="67" class="caltd"></td>
</tr>

</table>

<script type="text/javascript">
getMonth();

$(".caltd").click(function(){
    if ($(".subtd", this).attr("title") > 0) {
        {% if ui.readonly == 1 %}
            var dialog = '<div title="Выбранная дата" style="display: none; text-align: left; font-size: 12px"><p style="margin-bottom: 10px; text-align: center"><b>' + $(".subtd", this).text() + "-" + $("#month").val() + "-" + $("#year").val() + '</b></p><p><img src="/img/edititem.gif" alt="" style="vertical-align: middle" /> <a href="/tt/?date=' + $(".subtd", this).attr("title") + '">Перейти к этой дате</a></p></div>';
        {% else %}
            var dialog = '<div title="Выбранная дата" style="display: none; text-align: left; font-size: 12px"><p style="margin-bottom: 10px; text-align: center"><b>' + $(".subtd", this).text() + "-" + $("#month").val() + "-" + $("#year").val() + '</b></p><p style="margin-bottom: 5px"><img src="/img/plus-button.png" alt="" style="vertical-align: middle" /> <a href="/tt/add/?date=' + $(".subtd", this).attr("title") + '">Создать задачу</a></p><p><img src="/img/edititem.gif" alt="" style="vertical-align: middle" /> <a href="/tt/?date=' + $(".subtd", this).attr("title") + '">Перейти к этой дате</a></p></div>';
        {% endif %}
        
        $(dialog).dialog({ width: 200, height: 120 });
    }
});

function getMonth() {
    var arr = new Array();
    var data = "action=getMonth&month=" + $("#month").val() + "&year=" + $("#year").val();    
	$.ajax({
		type: "POST",
		url: "/ajax/index/",
		data: data,
        dataType: 'json',
		success: function(res) {
            $.each(res, function(key, val) {
                if (key == "first") {
                    first = val;
                } else if (key == "num") {
                    num = val;
                } else {
                    arr[key] = val;
                }
            });
            
            day = 1;
            for (k=1; k<=6; k++) {
                if (k == 1) {
                    for (l=1; l<first; l++) {
                        var tdid = "#" + 1 + l;
                        $(tdid).css("border", "0px none");
                        $(tdid).css("background-color", "#FFF");
                        $(tdid).html("");
                    }
                        
                    for (l=first; l<=7; l++) {
                        var tdid = "#" + 1 + l;
                        
                        if (day <= num) {
                            $(tdid).css("border", "1px solid #CCC");
                            if ((l == 6) || (l == 7)) {
                                $(tdid).css("background-color", "#F7F7F1");
                            }
                            if (day < 10) {
                                $(tdid).html("<p class='subtd' title='" + $("#year").val() + $("#month").val() + "0" + day + "'>" + "0" + day + "</p>");
                            } else {
                                $(tdid).html("<p class='subtd' title='" + $("#year").val() + $("#month").val() + day + "'>" + day + "</p>");
                            }
                            if (arr[day] != 0) {
                                $(tdid).append("<div>" + arr[day] + "</div>");
                            }
                            
                            if (day == {{ day }}) { $(tdid).css("border", "1px solid orange"); }
                            
                            day++;
                        } else {
                            $(tdid).css("border", "0px none");
                            $(tdid).css("background-color", "#FFF");
                            $(tdid).html("");
                        }
                    }
                } else {
                    for (l=1; l<=7; l++) {
                        var tdid = "#" + k + l;
                        
                        if (day <= num) {
                            $(tdid).css("border", "1px solid #CCC");
                            if ((l == 6) || (l == 7)) {
                                $(tdid).css("background-color", "#F7F7F1");
                            }
                            if (day < 10) {
                                $(tdid).html("<p class='subtd' title='" + $("#year").val() + $("#month").val() + "0" + day + "'>" + "0" + day + "</p>");
                            } else {
                                $(tdid).html("<p class='subtd' title='" + $("#year").val() + $("#month").val() + day + "'>" + day + "</p>");
                            }
                            if (arr[day] != 0) {
                                $(tdid).append("<div>" + arr[day] + "</div>");
                            }
                            
                            if (day == {{ day }}) { $(tdid).css("border", "1px solid orange"); }
                            
                            day++;
                        } else {
                            $(tdid).css("border", "0px none");
                            $(tdid).css("background-color", "#FFF");
                            $(tdid).html("");
                        }
                    }
                }
            }
		}
	})
}
</script>