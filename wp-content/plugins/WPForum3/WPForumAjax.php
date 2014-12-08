<?php
require_once("assets/Smarty/libs/Smarty.class.php");

/*
* Class:
* Author: Fredrik Fahlstad
*/

class WPForumAjax
{
	protected $smarty;
	protected $template_dir;
	protected $helper;

	function __construct()
	{
		$this->smarty = new Smarty();
		$this->template_dir = WPFPATH . "/tpls";
		$this->helper = new ForumHelper();
	}

	function marksolved()
	{
		$this->checkInput();
		$result = ForumHelper::markSolved($_REQUEST["record"]);
		$response = array(
			"affected_rows" => $result,
		);
		die(json_encode($response));
	}

	function checkInput()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "wpforum_ajax_nonce")) {
			exit("No naughty business please");
		}
	}
}