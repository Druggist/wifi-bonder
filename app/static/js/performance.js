$(document).ready(function(){
	$('.collapsible').collapsible();
	$('select').material_select();
});

$('#id').change(function(){
	if($( this ).val() != null) {
		window.location.replace("performance.php?id=" + $( this ).val());
	}
});

$("a[href='#new_data']").click(function(){
	$("#new_data").empty();
	$('.progress').removeClass('hide');
	$(this).addClass('disabled');
	$("#new_data").load( "ajax/get_performance.php", function(){
		$("a[href='#new_data']").removeClass('disabled');
		$('.progress').addClass('hide');
	});
});
