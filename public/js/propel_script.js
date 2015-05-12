function validate_data(){
	var child_feedback = document.getElementById('childFeedback').value.trim();
	var module_feedback = document.getElementById('moduleFeedback').value.trim();
	var title = document.getElementById('title').value.trim();
	if(child_feedback=="" && module_feedback==""){
		$('#errorMessage').html('<strong>Error</strong>: Feedback field is EMPTY');
		$('#errorMessage').fadeIn('slow');
		$('html,body').animate({ scrollTop: 0 },1000);
		return false;
	}
	else if(child_feedback!="" && title==""){
		$('#errorMessage').html('<strong>Error</strong>: Child feedback title is EMPTY');
		$('#errorMessage').fadeIn('slow');
		$('html,body').animate({ scrollTop: 0 },1000);
		return false;
	}
	else{	
		$('#errorMessage').fadeOut('slow');
	}
	
}

function validate_calendar_approval(){
	var checkboxes = document.getElementsByClassName('check_calendar');
	var count = 0;
	for (var i=0;i<checkboxes.length;i++){
		if(checkboxes.item(i).checked)
			count++
	}
	if(count==0){
		$('#errorMessageApproval').html('<strong>Error</strong>: EMPTY Selection');
		$('#errorMessageApproval').fadeIn('slow');
		$('html,body').animate({ scrollTop: 0 },1000);
		return false;
	}
	else{
		$('#errorMessageApproval').html('');
		$('#errorMessageApproval').fadeOut('slow');
		return true;
	}
}

function select_all(){
	var all = document.getElementById('select_all');
	var checkboxes = document.getElementsByClassName('check_calendar');
	if(all.checked){
		for (var i=0;i<checkboxes.length;i++){
			checkboxes.item(i).checked=true;
		}
	}	
	else{
		for (var i=0;i<checkboxes.length;i++){
			checkboxes.item(i).checked=false;
		}
	}	
}