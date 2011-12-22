<div style="overflow: hidden">

<!-- Блок выбор месяца-года -->
<div class="blockbd" style="float: right">
<p>
<span>
<select id="month" name="month">
    <option value="01"{% if month == 1 %}selected="selected"{% endif %}>январь</option>
    <option value="02"{% if month == 2 %}selected="selected"{% endif %}>февраль</option>
    <option value="03"{% if month == 3 %}selected="selected"{% endif %}>март</option>
    <option value="04"{% if month == 4 %}selected="selected"{% endif %}>апрель</option>
    <option value="05"{% if month == 5 %}selected="selected"{% endif %}>май</option>
    <option value="06"{% if month == 6 %}selected="selected"{% endif %}>июнь</option>
    <option value="07"{% if month == 7 %}selected="selected"{% endif %}>июль</option>
    <option value="08"{% if month == 8 %}selected="selected"{% endif %}>август</option>
    <option value="09"{% if month == 9 %}selected="selected"{% endif %}>сентябрь</option>
    <option value="10"{% if month == 10 %}selected="selected"{% endif %}>октябрь</option>
    <option value="11"{% if month == 11 %}selected="selected"{% endif %}>ноябрь</option>
    <option value="12"{% if month == 12 %}selected="selected"{% endif %}>декабрь</option>
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
<!-- Блок выбор месяца-года -->

<!-- Блок выбор типа задач для вывода -->
<div class="blockbd" style="float: left; font-size: 11px">
<p><label><input type="radio" name="caltask" value="0" class="caltask" {% if caltype == 0 %}checked="checked"{% endif %} /><span style="position: relative; bottom: 3px">задачи, где я <b>ответственный</b></span></label></p>
<p><label><input type="radio" name="caltask" value="1" class="caltask" {% if caltype == 1 %}checked="checked"{% endif %} /><span style="position: relative; bottom: 3px">задачи, где я <b>автор</b></span></label></p>
</div>
<!-- END Блок выбор типа задач для вывода -->

</div>

<!-- Блок количеcтво задач по типам -->
<div style="overflow: hidden; background-color: #DFE4EA; font-size: 11px; padding: 0 5px 3px; margin-top: 10px">

<span style="position: static">
<a style="text-decoration: none" title="периодические" href="{{ registry.uri }}tt/task/iter/"><img style="position: relative; top: 3px" src="{{ registry.uri }}img/calendar-blue.png" alt="" border="0" /> периодические</a> <b>[{{ itertask }}]</b>
</span>

<span style="position: static; margin-left: 10px">
<a style="text-decoration: none" title="ограниченные по времени" href="{{ registry.uri }}tt/task/time/"><img src="{{ registry.uri }}img/alarm-clock.png" alt="" border="0" style="vertical-align: middle" /> ограниченные по времени</a> <b>[{{ timetask }}]</b>
</span>

{% set noiter = allmytask - itertask - timetask %}
<span style="position: static; margin-left: 10px">
<a style="text-decoration: none" title="неограниченные по времени" href="{{ registry.uri }}tt/task/noiter/"><img src="{{ registry.uri }}img/clock.png" alt="" border="0" style="vertical-align: middle" /> неограниченные по времени</a> <b>[{{ noiter }}]</b>
</span>

<span style="position: static; margin-left: 10px">
<img src="{{ registry.uri }}img/flag.png" alt="" style="vertical-align: middle" /> закрытые
</span>

</div>
<!-- END Блок количество задач по типам -->

<table id="ajax-load" style="width: 100%; padding: 100px 30px 0 0"><tr><td style="text-align: center"><img src="{{ registry.uri }}img/ajax-loader.gif" alt="ajax-loader.gif" border="0" /></td></tr></table>

<table id="cal" cellpadding="3" cellspacing="2" width="100%" style="padding: 10px 50px 0 0; display: none">

<tr>
<td width="14%" class="weekday workday">Понедельник</td>
<td width="14%" class="weekday workday">Вторник</td>
<td width="14%" class="weekday workday">Среда</td>
<td width="14%" class="weekday workday">Четверг</td>
<td width="14%" class="weekday workday">Пятница</td>
<td width="14%" class="weekday holiday">Суббота</td>
<td width="14%" class="weekday holiday">Воскресенье</td>
</tr>

<tr style="height: 20px">
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
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

$(".caltask").click(function() {
	var data = "action=setCalTask&caltask=" + $(this).val();
	$.ajax({
		type: "POST",
		url: "{{ registry.uri }}ajax/tt/",
		data: data,
		success: function(res) {
			document.location.href = document.location.href;
		}
	});
});

function getMonth() {
    $("#ajax-load").show();
    $("#cal").hide();
    
    var arr = new Array();
    var data = "action=getMonth&month=" + $("#month").val() + "&year=" + $("#year").val();
	$.ajax({
		type: "POST",
		url: "{{ registry.uri }}ajax/tt/",
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
                            $(tdid).css("border", "1px solid #6696af");
                            if ((l == 6) || (l == 7)) {
                                $(tdid).css("background-color", "#FFF");
                            }
                            if (day < 10) {
                                tdate = $("#year").val() + $("#month").val() + "0" + day;
                                fdate = "0" + day + "." + $("#month").val();
                                addtask = '<a style="float: left" href="{{ registry.uri }}tt/add/?date=' + tdate + '" title="создать задачу"><img src="{{ registry.uri }}img/plus-button.png" alt="" style="position: relative; top: 4px; border: 0" /></a>';
                                
                                $(tdid).html("<p class='subtd'>" + addtask + fdate + "</p>");
                                
                                if (arr[day] != 0) {
                                    $(tdid).append("<div class='calcont' onclick='refreshurl(\"{{ registry.uri }}tt/date/" + tdate + "/\")' title='Перейти к дате'>" + arr[day] + "</div>");
                                } else {
                                    $(tdid).append("<div class='calcont' onclick='refreshurl(\"{{ registry.uri }}tt/date/" + tdate + "/\")' title='Перейти к дате'>&nbsp;</div>");
                                }
                            } else {
                                tdate = $("#year").val() + $("#month").val() + day;
                                fdate = day + "." + $("#month").val();
                                addtask = '<a style="float: left" href="{{ registry.uri }}tt/add/?date=' + tdate + '" title="создать задачу"><img src="{{ registry.uri }}img/plus-button.png" alt="" style="position: relative; top: 4px; border: 0" /></a>';
                                
                                $(tdid).html("<p class='subtd'>" + addtask + fdate + "</p>");
                                
                                if (arr[day] != 0) {
                                    $(tdid).append("<div class='calcont' onclick='refreshurl(\"{{ registry.uri }}tt/date/" + tdate + "/\")' title='Перейти к дате'>" + arr[day] + "</div>");
                                } else {
                                    $(tdid).append("<div class='calcont' onclick='refreshurl(\"{{ registry.uri }}tt/date/" + tdate + "/\")' title='Перейти к дате'>&nbsp;</div>");
                                }
                            }
                            
                            if (day == {{ day }}) { $(tdid).css("border", "2px solid red"); }
                            
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
                            $(tdid).css("border", "1px solid #6696af");
                            if ((l == 6) || (l == 7)) {
                                $(tdid).css("background-color", "#FFF");
                            }
                            if (day < 10) {
                                tdate = $("#year").val() + $("#month").val() + "0" + day;
                                fdate = "0" + day + "." + $("#month").val();
                                addtask = '<a style="float: left" href="{{ registry.uri }}tt/add/?date=' + tdate + '" title="создать задачу"><img src="{{ registry.uri }}img/plus-button.png" alt="" style="position: relative; top: 4px; border: 0" /></a>';
                                
                                $(tdid).html("<p class='subtd'>" + addtask + fdate + "</p>");

                                if (arr[day] != 0) {
                                    $(tdid).append("<div class='calcont' onclick='refreshurl(\"{{ registry.uri }}tt/date/" + tdate + "/\")' title='Перейти к дате'>" + arr[day] + "</div>");
                                } else {
                                    $(tdid).append("<div class='calcont' onclick='refreshurl(\"{{ registry.uri }}tt/date/" + tdate + "/\")' title='Перейти к дате'>&nbsp;</div>");
                                }
                            } else {
                                tdate = $("#year").val() + $("#month").val() + day;
                                fdate = day + "." + $("#month").val();
                                addtask = '<a style="float: left" href="{{ registry.uri }}tt/add/?date=' + tdate + '" title="создать задачу"><img src="{{ registry.uri }}img/plus-button.png" alt="" style="position: relative; top: 4px; border: 0" /></a>';
                                
                                $(tdid).html("<p class='subtd'>" + addtask + fdate + "</p>");
                                
                                if (arr[day] != 0) {
                                    $(tdid).append("<div class='calcont' onclick='refreshurl(\"{{ registry.uri }}tt/date/" + tdate + "/\")' title='Перейти к дате'>" + arr[day] + "</div>");
                                } else {
                                    $(tdid).append("<div class='calcont' onclick='refreshurl(\"{{ registry.uri }}tt/date/" + tdate + "/\")' title='Перейти к дате'>&nbsp;</div>");
                                }
                            }
                            
                            if (day == {{ day }}) { $(tdid).css("border", "2px solid red"); }
                            
                            day++;
                        } else {
                            $(tdid).css("border", "0px none");
                            $(tdid).css("background-color", "#FFF");
                            $(tdid).html("");
                        }
                    }
                }
            }

            $("#ajax-load").hide();
            $("#cal").show();
		}
	})
}
</script>