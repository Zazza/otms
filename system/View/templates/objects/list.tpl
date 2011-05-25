<form method="post" action="{{ uri }}objects/list/">

<p style="margin-top: 10px"><b>Критерий (<a href="{{ uri }}objects/?clear">сбросить</a>):</b></p>
<select name="criterion">
<option value="0" {% if criterion == 0 %}selected="selected"{% endif %}>выводить все</option>
<option value="1" {% if criterion == 1 %}selected="selected"{% endif %}>по дате добавления объекта</option>
<option value="2" {% if criterion == 2 %}selected="selected"{% endif %}>по дате последнего изменения объекта</option>
<option value="3" {% if criterion == 3 %}selected="selected"{% endif %}>по дате добавления дополнительной информации</option>
<option value="4" {% if criterion == 4 %}selected="selected"{% endif %}>по дате добавления задачи</option>
<option value="5" {% if criterion == 5 %}selected="selected"{% endif %}>по дате комментария к задаче</option>
<option value="6" {% if criterion == 6 %}selected="selected"{% endif %}>по дате закрытия задачи</option>
</select>

<p style="margin-top: 10px"><b>Укажите диапазон дат для выбора объектов:</b></p>

<div style="height: 30px">
<div style="float: left; width: 40px">С:</div>
<div style="float: left"><input name="sday" id="sday" type="text" class="selected" value="{{ sday|e }}" /></div>
<div style="float: left">
<select id="smonth" name="smonth">
<option value="1"{% if smonth == 1 %}selected="selected"{% endif %}>января</option>
<option value="2"{% if smonth == 2 %}selected="selected"{% endif %}>февраля</option>
<option value="3"{% if smonth == 3 %}selected="selected"{% endif %}>марта</option>
<option value="4"{% if smonth == 4 %}selected="selected"{% endif %}>апреля</option>
<option value="5"{% if smonth == 5 %}selected="selected"{% endif %}>мая</option>
<option value="6"{% if smonth == 6 %}selected="selected"{% endif %}>июня</option>
<option value="7"{% if smonth == 7 %}selected="selected"{% endif %}>июля</option>
<option value="8"{% if smonth == 8 %}selected="selected"{% endif %}>августа</option>
<option value="9"{% if smonth == 9 %}selected="selected"{% endif %}>сентября</option>
<option value="10"{% if smonth == 10 %}selected="selected"{% endif %}>октября</option>
<option value="11"{% if smonth == 11 %}selected="selected"{% endif %}>ноября</option>
<option value="12"{% if smonth == 12 %}selected="selected"{% endif %}>декабря</option>
</select>
</div>
<div style="float: left"><input name="syear" id="syear" type="text" class="selected" value="{{ syear|e }}" /></div>
<div style="float: left"><input type="text" id="sbut" value="выбрать дату" style="cursor: pointer" /></div>
</div>

<div style="height: 30px">
<div style="float: left; width: 40px">По:</div>
<div style="float: left"><input name="fday" id="fday" type="text" class="selected" value="{{ fday|e }}" /></div>
<div style="float: left">
<select id="fmonth" name="fmonth">
<option value="1"{% if fmonth == 1 %}selected="selected"{% endif %}>января</option>
<option value="2"{% if fmonth == 2 %}selected="selected"{% endif %}>февраля</option>
<option value="3"{% if fmonth == 3 %}selected="selected"{% endif %}>марта</option>
<option value="4"{% if fmonth == 4 %}selected="selected"{% endif %}>апреля</option>
<option value="5"{% if fmonth == 5 %}selected="selected"{% endif %}>мая</option>
<option value="6"{% if fmonth == 6 %}selected="selected"{% endif %}>июня</option>
<option value="7"{% if fmonth == 7 %}selected="selected"{% endif %}>июля</option>
<option value="8"{% if fmonth == 8 %}selected="selected"{% endif %}>августа</option>
<option value="9"{% if fmonth == 9 %}selected="selected"{% endif %}>сентября</option>
<option value="10"{% if fmonth == 10 %}selected="selected"{% endif %}>октября</option>
<option value="11"{% if fmonth == 11 %}selected="selected"{% endif %}>ноября</option>
<option value="12"{% if fmonth == 12 %}selected="selected"{% endif %}>декабря</option>
</select>
</div>
<div style="float: left"><input name="fyear" id="fyear" type="text" class="selected" value="{{ fyear|e }}" /></div>
<div style="float: left"><input type="text" id="fbut" value="выбрать дату" style="cursor: pointer" /></div>
</div>


<p><input type="submit" name="submit" value="Выбрать" /></p>

</form>

<hr style="border: 0px; background-color: #EEE; margin: 20px 0; height: 1px" />

<script type="text/javascript">
$('#sbut').datepicker({
    dayName: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    firstDay: 1,
    defaultDate: $("#smonth").val() + "/" + $("#sday").val() + "/" + $("#syear").val(),
	onSelect: function(dateText, inst) {
		$("#sbut").val("выбрать дату");
		$("#sday").val(inst.selectedDay);
		$("#syear").val(inst.selectedYear);
		var month = inst.selectedMonth + 1;
		$("#smonth [value='" + month + "']").attr("selected", "selected");
	}
});

$("#fbut").datepicker({
dayName: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    firstDay: 1,
    defaultDate: $("#fmonth").val() + "/" + $("#fday").val() + "/" + $("#fyear").val(),
	onSelect: function(dateText, inst) {
		$("#fbut").val("выбрать дату");
		$("#fday").val(inst.selectedDay);
		$("#fyear").val(inst.selectedYear);
		var month = inst.selectedMonth + 1;
		$("#fmonth [value='" + month + "']").attr("selected", "selected");
	}
});
</script>