$(document).ready(function(){
	$('.modal').modal();
});

$("a[href='#show_paste']").click(function(){
	$("#paste_content").load( "ajax/show_paste.php?id="+$(this).attr("data-id"), function(){});
});