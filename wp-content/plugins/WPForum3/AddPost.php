<?php
require_once("assets/guid.php");
require_once("ForumHelper.php");

global $wpdb;
/* Sanitize input */
$thread_id = ForumHelper::input_filter($_REQUEST["record"]);
$subject = ForumHelper::input_filter($_REQUEST["subject"]);
$text = ForumHelper::input_filter($_REQUEST["text"]);

$user_id = get_current_user_id();
$date = date("Y-m-d H:i:s");

/* Add thread */
$post_id = create_guid();

$sql_post = "INSERT INTO " . AppBase::$posts_table . "
	(
		subject,
		id,
		text,
		parent_id,
		date,
		user_id
	)
	VALUES(
		'$subject',
		'$post_id',
		'$text',
		'$thread_id',
		'$date',
		'$user_id'
		)";


$wpdb->query($sql_post);

$redirect_url = ForumHelper::getLink(AppBase::THREAD_VIEW_ACTION, $thread_id);
