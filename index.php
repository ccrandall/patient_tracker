<?
session_start(); 
if(!$_SESSION['logged_in']){ 
    header("Location: login.php"); 
    exit; 
}

if ($_GET['logout'] == 1) {
	unset($_SESSION['logged_in']);
	header("Location: login.php"); 
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
	<link rel="stylesheet" href="css/jquery.ui.datepicker.mobile.css" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script>
		//reset type=date inputs to text
		$( document ).bind( "mobileinit", function(){
			$.mobile.page.prototype.options.degradeInputs.date = true;
		});	
$(document).on("mobileinit", function () {
	$.mobile.ajaxEnabled = false;
    $.mobile.pushStateEnabled = false;
});		
	</script>
	<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
	<script src="js/jQuery.ui.datepicker.js"></script>
	<script src="js/jquery.ui.datepicker.mobile.js"></script>
 <script>
  $(function() {
  
	/*function highlightDays() {
		$.ajax({
			url: "api.php",
			type: "POST",
			//dataType: 'json',
			success: function(data) {
				//data = JSON.parse(data);
				console.log(data);
			}
		});
	};*/
    $("#datepicker_view").datepicker({
    	dateFormat: 'yy-mm-dd',
    	//beforeShowDay: highlightDays(),
    	onSelect: function(date) {
    		// put selected date into object
    		//var data = {'date': date, 'action': 'view'};
    		var data = {'date': date};
    		//console.log(data);
			$('input[name="date"]').val(date);    		
    		$.ajax({
    			url: "api.php",
				cache: false,
    			data: data,
				dataType: 'json',
				success: function(data, textStatus, errorThrown) {
					if (data == false) {
						$("#view_date_output").html('<table><tr><td>&nbsp;</td></tr><tr><td>No appointments on this date.</td></tr><tr><td>&nbsp;</td></tr></table>');
						$("#new_patients input[type='checkbox'], #follow_up_patients input[type='checkbox']").prop("checked", false);
						$(".ui-checkbox label").removeClass("ui-checkbox-on").attr("data-icon","checkbox-off").find(".ui-icon").removeClass("ui-icon-checkbox-on").addClass("ui-icon-checkbox-off");
						//console.log(data);
					} else {
						$(".ui-checkbox label").removeClass("ui-checkbox-on").attr("data-icon","checkbox-off").find(".ui-icon").removeClass("ui-icon-checkbox-on").addClass("ui-icon-checkbox-off");
						console.log(data);
						console.log(textStatus);
						//console.log(errorThrown);						
						for(var i=0;i<=data.new_patients;i++) {
							// var total = data[1].split(",").length;
							if (i >= 1) {
								$("input#patient-" + i).prop("checked", true).next().attr("data-icon","checkbox-on").addClass("ui-checkbox-on").find(".ui-icon").addClass("ui-icon-checkbox-on").removeClass("ui-icon-checkbox-off");
							}
						}
						/* clear checkbox value */
						max_new_patients = 8;
						clear_checkbox = parseInt(data.new_patients) + 1;

						for (var c=clear_checkbox; c<=max_new_patients; c++) {
							$("input#patient-" + c).prop("checked", false);
						}						

						for(var i=0;i<=data.follow_up;i++) {
							// var total = data[1].split(",").length;
							if (i >= 1) {
								$("input#follow-up-" + i).prop("checked", true).next().attr("data-icon","checkbox-on").addClass("ui-checkbox-on").find(".ui-icon").addClass("ui-icon-checkbox-on").removeClass("ui-icon-checkbox-off");
							}							
						}
						/* clear checkbox value */
						max_follow_up = 20;
						clear_checkbox = parseInt(data.follow_up) + 1;

						for (var c=clear_checkbox; c<=max_follow_up; c++) {
							$("input#follow-up-" + c).prop("checked", false);
						}						

						/*if (data.new_patients > 2) {
							data_text_color = 'red';
						} else {
							data_text_color = 'green';
						}
						if (data.follow_up >= 10) {
							text_color = 'red';
						} else {
							text_color = 'green';
						}*/
						data_text_color = '';
						text_color = '';				
						$("#view_date_output").html('<table><tr><td>Date: ' + data.date + '</td>' + '</tr><tr><td class="' + data_text_color + '">Total New Patients: ' + data.new_patients + '</td></tr><tr><td class="'+ text_color +'">Total Follow Ups: ' + data.follow_up + '</td></tr></table>' );
						//$("#new_patients input[type='checkbox'], #follow_up_patients input[type='checkbox']").prop("checked", false);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
 					console.log(jqXHR, textStatus, errorThrown);
				}
    		});	
    	}
  	});
	$("#form_submit").click(function() {
		current_date = $('input[name="date"]').val();

		//var new_patient_count = $("#new_patients input[type='checkbox']:checked").length;

		//var follow_up_count = $("#follow_up_patients input[type='checkbox']:checked").length;
		var submit_data = '';
		var submit_data = $("#patient_calendar").serialize();

		// var submit_data = {'date': current_date, 'checkboxes': 'checkboxes="'+ checkboxes +'"'};
		//console.log(submit_data);
		$.ajax({
			url: "submit.php",
			cache: false,
			type: "POST",
			data: submit_data,
			dataType: 'json',
			success: function(returned_data, textStatus, errorThrown) {
				console.log('returned_data: ' + returned_data);
				//console.log('date: ' + returned_data.date);
				console.log('textStatus: ' + textStatus);
				console.log(errorThrown);				
				$("#view_date_output").html("<table><tr><td>&nbsp;</td></tr><tr><td>Calendar updated.</td></tr><tr><td>&nbsp;</td></tr></table>");
				//return submit_data;
				//highlightFullDays();
			},
			error: function(xhr, status, text) {
				var response = $.parseJSON(xhr.responseText);

				console.log('Failure!');

				if (response) {
					console.log(response.error);
				} else {
					// This would mean an invalid response from the server - maybe the site went down or whatever...
				}
			}
		});
	});  	
  });	
  </script>
	<style>
		body {
			font-family: 'Open Sans';
			font-size: 14px;
		}
	</style>
</head>
<body>
	<div data-role="page" class="">	
		<div data-role="collapsible-set" data-theme="a" data-content-theme="d">
			<div class="ui-grid-a ui-responsive">
				<form action="submit.php" method="POST" id="patient_calendar">
				<div data-role="collapsible" data-collapsed="false">
					<h3>Patient Tracker</h3>
					<div class="ui-block-a">
						<div class="ui-bar">
							<div id="datepicker_view"></div>
							<input name="date" type="hidden" value="">
						</div>
					</div>
					<div class="ui-block-b">
						<div class="ui-bar">					
							<div id="view_date_output" class=""></div>
							<!--<input type="submit" value="submit" data-role="button" data-inline="true" data-icon="check" data-theme="b">-->
							<p><a href="#" id="form_submit" data-role="button" data-inline="true" data-icon="check" data-theme="b">Update Calendar</a></p>
						</div>
					</div>
					<div class="ui-block-a">
						<div class="ui-bar">
							<div id="new_patients">
								<fieldset data-role="controlgroup">
									<legend>New Patients:</legend>
									<?
										$i = 1;
										$numeric = array(
											'1'=>'One',
											'2'=>'Two',
											'3'=>'Three',
											'4'=>'Four',
											'5'=>'Five',
											'6'=>'Six',
											'7'=>'Seven',
											'8'=>'Eight',
											'9'=>'Nine',
											'10'=>'Ten',
											'11'=>'Eleven',
											'12'=>'Twelve',
											'13'=>'Thirteen',
											'14'=>'Fourteen',
											'15'=>'Fifteen',
											'16'=>'Sixteen',
											'17'=>'Seventeen',
											'18'=>'Eighteen',
											'19'=>'Nineteen',
											'20'=>'Twenty'
										);
										while ($i <= 8) {
											echo '<input type="checkbox" name="new_patient_values[]" id="patient-'.$i.'">';
											echo '<label for="patient-'.$i.'">'. $numeric[$i] .'</label>';
											$i++;
										}
									?>
								</fieldset>
							</div>
						</div>
					</div>
					<div class="ui-block-b">
						<div class="ui-bar">
							<div id="follow_up_patients">
								<fieldset data-role="controlgroup">
									<legend>Follow Up Patients:</legend>
									<?
										$j = 1;
										while ($j <= 20) {
											echo '<input type="checkbox" name="follow_up_values[]" id="follow-up-'.$j.'">';
											echo '<label for="follow-up-'.$j.'">'. $numeric[$j] .'</label>';
											$j++;
										}
									?>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>
