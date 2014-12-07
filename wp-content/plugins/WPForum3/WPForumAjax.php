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
	function __construct(){
		$this->smarty = new Smarty();
		$this->template_dir = WPFPATH . "/tpls";
		$this->helper = new ForumHelper();
	}
	function newthread()
	{
		echo "<pre>";
		print_r($_REQUEST);
		echo "</pre>";
		$this->checkInput();
		$this->smarty->assign("record", $_REQUEST["record"]);
		$this->smarty->display($this->template_dir . "/new_thread_form.tpl");
		die();
	}

	function checkInput(){
		if (!wp_verify_nonce($_REQUEST['nonce'], "wpforum_nonce")) {
			exit("No naughty business please");
		}
		if(!is_numeric($_REQUEST["record"])){
			exit("No naughty business please");
		}
	}
}