jQuery(function ($) {

	$("#forum-form-new-thread").validate();


	/*$(".new_thread").on("click", function () {
	 openPopup("newthread", $(this));
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

	 function openPopup(action, element) {

	 var data = {
	 action: action,
	 record: element.data("forum-id"),
	 nonce: element.data("nonce")
	 };

	 $("#forum-dialog").dialog({
	 modal: true,
	 width: "60%",
	 height: "auto",
	 title: "WP Forum",
	 position: {
	 my: "center",
	 at: "center",
	 of: $("body"),
	 within: $("body")
	 },
	 open: function () {
	 $(this).load(forumAjax.ajaxurl, data);
	 },
	 close: function (event, ui) {
	 $("#forum-dialog").html("");
	 },
	 buttons: {
	 OK: function () {
	 },
	 CANCEL: function () {
	 $(this).dialog("close");
	 }
	 }
	 });
	 }
	 */
});


