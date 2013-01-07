$(document).ready(function(){
  $("table.step").click(function(){    
	if($(this).next().length) {
		$(this).toggle();	
		$(this).next().toggle();
	} else {
		$(this).toggle();
		$("#step1").toggle();
	}
  });
});