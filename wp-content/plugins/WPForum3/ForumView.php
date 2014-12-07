<?php
require_once("assets/Smarty/libs/Smarty.class.php");
require_once("ForumHelper.php");

/*
* Class:
* Author: Fredrik Fahlstad
*/

class ForumView
{
	protected $smarty;
	protected $template_dir;
	protected $helper;
	protected $action;
	protected $record;
	protected $offset;

	public function __construct($action, $record, $offset)
	{
		$this->action = $action;
		$this->record = $record;
		$this->offset = $offset;

		$this->template_dir = WPFPATH . "/tpls";
		$this->smarty = new Smarty();
		$this->helper = new ForumHelper();
		$this->assignMisc();
		$this->assignButtons();
	}

	function getNewThreadView()
	{
		//$this->smarty->assign("action", WPFURL."AddThread.php");
		$this->smarty->assign("record", $_REQUEST["record"]);
		return $this->smarty->fetch($this->template_dir . "/new_thread_form.tpl");
	}

	/*
	* @param
	* @return
	*/
	public function assignButtons()
	{
		$nonce = wp_create_nonce("wpforum_nonce");
		$buttons = array(
			AppBase::FORUM_VIEW_ACTION => array(
				"new_thread" => "<a data-nonce='" . $nonce . "' data-forum-id='" . $this->record . "' class='forum-button new_thread' href='" . ForumHelper::getLink(AppBase::NEW_THREAD_VIEW_ACTION, $this->record) . "'>Start Topic</a>",
			),
			AppBase::THREAD_VIEW_ACTION => array(
				"new_post" => "<a data-nonce='" . $nonce . "' data-thread-id='" . $this->record . "' class='forum-button new_post' href='" . ForumHelper::getLink(AppBase::NEW_POST_VIEW_ACTION, $this->record) . "'>Reply</a>",
				"subscribe_rss" => "<a data-nonce='" . $nonce . "' class='forum-button subscribe_rss' href='" . ForumHelper::getLink(AppBase::RSS_POST_ACTION, $this->record) . "'>RSS Feed</a>",
				"subscribe_email" => "<a data-nonce='" . $nonce . "'class='forum-button subscribe_email' href='" . ForumHelper::getLink(AppBase::EMAIL_POST_ACTION, $this->record) . "'>Email Subscription</a>"
			),
		);
		$buttons[$this->action];
		$this->smarty->assign("buttons", $buttons[$this->action]);
	}

	/*
	* @param
	* @return
	*/
	public function assignMisc()
	{
		$config = array(
			"date_format" => "%B %e, %Y",
		);

		$this->smarty->assign("border", AppBase::$border);
		$this->smarty->assign("forum_table_class", "forum-table");
		$this->smarty->assign("config", $config);
	}

	/*
	* @param
	* @return string
	*/
	public function getForumView()
	{
		$this->smarty->assign("data", $this->helper->getThreadsInForum($this->record, $this->offset));
		return $this->smarty->fetch($this->template_dir . "/threads.tpl");
	}

	/*
	* @param $action string
	* @param $record string
	* @return string
	*/
	public function getTopicView()
	{
		$this->smarty->assign("data", $this->helper->getPostsInThread($this->record, $this->offset));
		return $this->smarty->fetch($this->template_dir . "/posts.tpl");
	}

	/*
	* @param $action string
	* @param $record string
	* @return string
	*/
	public function getMainView()
	{
		$this->smarty->assign("data", $this->helper->getCategories());
		return $this->smarty->fetch($this->template_dir . "/main.tpl");
	}
}

?>
