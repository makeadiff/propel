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