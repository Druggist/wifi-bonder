$(document).ready(function(){
	$('.modal').modal();
	$('select').material_select();
});

$("a[href='#show_networks']").click(function(){
	$("#networks_content").load( "ajax/show_networks.php?iface="+$(this).attr("data-iface"), function(){
		$('.modal-pass').modal();

		$("a[href='#connect']").click(function(){
			$("#connect_iface").val($(this).attr("data-iface"));
			$("#connect_ssid").val("sadasdasd");
		});
	});
});
