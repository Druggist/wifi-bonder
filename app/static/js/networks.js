$(document).ready(function(){
	$('.modal').modal();
});

$("a[href='#show_networks']").click(function(){
	$("#networks_content").load( "ajax/show_networks.php?iface="+$(this).attr("iface"), function(){});
});

 $(document).ready(function(){
    $('.collapsible').collapsible();
  });