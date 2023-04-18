/**
 - dob.js
 - Custom Date Of Birth
 - Project: Corona website Portal
 **/

 var appDateOfBirth = {
 	createField: function(properties) {
 		var day = '<select name="dob-day" class="dob_param" id="'+properties.dayId+'" data-day="'+properties.dayId+'" data-month="'+properties.monthId+'" data-year="'+properties.yearId+'" data-input="'+properties.inputId+'">'+
 		'<option value="">Day</option>'+
 		'<option value="01">01</option>'+
 		'<option value="02">02</option>'+
 		'<option value="03">03</option>'+
 		'<option value="04">04</option>'+
 		'<option value="05">05</option>'+
 		'<option value="06">06</option>'+
 		'<option value="07">07</option>'+
 		'<option value="08">08</option>'+
 		'<option value="09">09</option>'+
 		'<option value="10">10</option>'+
 		'<option value="11">11</option>'+
 		'<option value="12">12</option>'+
 		'<option value="13">13</option>'+
 		'<option value="14">14</option>'+
 		'<option value="15">15</option>'+
 		'<option value="16">16</option>'+
 		'<option value="17">17</option>'+
 		'<option value="18">18</option>'+
 		'<option value="19">19</option>'+
 		'<option value="20">20</option>'+
 		'<option value="21">21</option>'+
 		'<option value="22">22</option>'+
 		'<option value="23">23</option>'+
 		'<option value="24">24</option>'+
 		'<option value="25">25</option>'+
 		'<option value="26">26</option>'+
 		'<option value="27">27</option>'+
 		'<option value="28">28</option>'+
 		'<option value="29">29</option>'+
 		'<option value="30">30</option>'+
 		'<option value="31">31</option>'+
 		'</select>';
 		var month='<select name="dob-month" class="dob_param" id="'+properties.monthId+'" data-day="'+properties.dayId+'" data-month="'+properties.monthId+'" data-year="'+properties.yearId+'" data-input="'+properties.inputId+'">'+
 		'<option value="">Month</option>'+
 		'<option value="01">January</option>'+
 		'<option value="02">February</option>'+
 		'<option value="03">March</option>'+
 		'<option value="04">April</option>'+
 		'<option value="05">May</option>'+
 		'<option value="06">June</option>'+
 		'<option value="07">July</option>'+
 		'<option value="08">August</option>'+
 		'<option value="09">September</option>'+
 		'<option value="10">October</option>'+
 		'<option value="11">November</option>'+
 		'<option value="12">December</option>'+
 		'</select>';
 		var year='<select name="dob-year" class="dob_param" id="'+properties.yearId+'" data-day="'+properties.dayId+'" data-month="'+properties.monthId+'" data-year="'+properties.yearId+'" data-input="'+properties.inputId+'">'+
 		'<option value="">Year</option>';

 		var end = parseInt(new Date().getFullYear());
 		for (var i = end; i >= 1900; i--) {
 			year += '<option value="'+i+'">'+i+'</option>';
 		}
 		year += '</select>';
 		$('#'+properties.containerId).html(day+month+year);
 	}
 }

 $(document).on('change', '.dob_param', function(){
 	var day_id = $(this).data('day');
 	var month_id = $(this).data('month');
 	var year_id = $(this).data('year');
 	var input_id = $(this).data('input');

 	var date = $('#'+day_id).val()+"-"+$('#'+month_id).val()+"-"+$('#'+year_id).val();
 	if($('#'+day_id).val().length == 0 && $('#'+month_id).val().length == 0 && $('#'+year_id).val().length == 0) {
 		date = "";
 	}

 	$('#'+input_id).val(date);
 	$('#'+input_id).next().html('');
 });