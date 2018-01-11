$(document).ready(function(){
	$('.collapsible').collapsible();
	$('select').material_select();
});

$('#id').change(function(){
	if($( this ).val() != null) {
		window.location.replace("performance.php?id=" + $( this ).val());
	}
});
