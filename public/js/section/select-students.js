$(function() {
	$("#filter").on('keyup', function(e) {
		var filter = this.value.toLowerCase();

		$("#students > option").each(function(){ 
			if(this.text.toLowerCase().search(filter) == -1) $(this).hide();
			else $(this).show();
		});
	});
})