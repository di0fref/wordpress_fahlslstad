jQuery(function ($) {
	$(".new_thread").on("click", function () {
		openPopup("new_thread");
		return false;
	});
	$(".new_post").on("click", function () {
		return false;
	});
	$(".subscribe_rss").on("click", function () {
		return false;
	});
	$(".subscribe_email").on("click", function () {
		return false;
	});

	function openPopup(){
		$("#forum-dialog").dialog({
			modal: true
		});

		$.ajax({
				url: "index.php",
				data:{
					module: "",
					action: "",
					to_pdf: true,
					record: $("input[name=record]").val()
					},
					beforeSend: function(){

					},
					success: function(){

					}
			});
	}

});


