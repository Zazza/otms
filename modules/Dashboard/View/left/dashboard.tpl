{#<div id="datepicker"></div>#}
<input type="text" id="datedash" value="выбрать дату" style="cursor: pointer" />
<input type="hidden" name="date" id="date" />
<p style="margin-top: 20px"><b>Инфо в dashboard:</b></p>

<p>
	<label>
	<input type="checkbox" id="task" name="task" {% if notify.task %}checked="checked"{% endif %} />
	задачи
	</label>
</p>

<p>
	<label>
	<input type="checkbox" id="com" name="com" {% if notify.com %}checked="checked"{% endif %} />
	комментарии
	</label>
</p>

<p>
	<label>
	<input type="checkbox" id="mail" name="mail" {% if notify.mail %}checked="checked"{% endif %} />
	почта
	</label>
</p>

<p>
	<label>
	<input type="checkbox" id="obj" name="obj" {% if notify.obj %}checked="checked"{% endif %} />
	объекты
	</label>
</p>

<p>
	<label>
	<input type="checkbox" id="info" name="info" {% if notify.info %}checked="checked"{% endif %} />
	информация
	</label>
</p>

<p style="margin-top: 20px">
<img style="position: relative; top: 4px" src="{{ registry.uri }}img/cross-small.png" alt="" border="0" />
<a href="{{ registry.uri }}dashboard/?clear">сбросить</a>
</p>

<script type="text/javascript">
$(document).ready(function() {
	$("#datedash").val('{{ formatDate }}');
	
	$('#datedash').datepicker({
		changeYear: true,
		changeMonth: true,
	    dayName: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
	    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
	    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
	    monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
	    firstDay: 1,
	    defaultDate: "{{ date }}",
		onSelect: function(dateText, inst) {
			$("#datedash").val('');
			$("#date").val(dateText);
			setDash();
		}
	});
});

$("input[type='checkbox']").click(function(){
	setDash();
});

function setDash() {
	if ($("input#task").attr("checked")) { var task = 1; } else { var task = 0; };
	if ($("input#com").attr("checked")) { var com = 1; } else { var com = 0; };
	if ($("input#obj").attr("checked")) { var obj = 1; } else { var obj = 0; };
	if ($("input#info").attr("checked")) { var info = 1; } else { var info = 0; };
	if ($("input#mail").attr("checked")) { var mail = 1; } else { var mail = 0; };

	var data = "action=setNotify&date=" + $("#date").val() + "&task=" + task + "&com=" + com + "&obj=" + obj + "&info=" + info + "&mail=" + mail;
	$.ajax({
	        type: "POST",
	        url: "{{ registry.uri }}ajax/dashboard/",
	        data: data,
	        success: function(res) {
				document.location.href = "{{ registry.uri}}dashboard/";
	        }
	});
};
</script>