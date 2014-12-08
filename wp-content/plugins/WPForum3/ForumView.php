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
		$this->assignTrail();
	}

	function getNewThreadView()
	{
		$this->smarty->assign("record", $_REQUEST["record"]);
		$this->smarty->assign("nonce", wp_create_nonce("wpforum_insert_nonce"));
		return $this->smarty->fetch($this->template_dir . "/new_thread_form.tpl");
	}

	function getNewPostView()
	{
		if (isset($_REQUEST[AppBase::FORUM_QUOTE])) {
			$post = $this->helper->getPost($_REQUEST[AppBase::FORUM_QUOTE]);
			$quote_data = array(
				"post" => $post,
				"user" => $this->helper->getUserDataFiltered($post["user_id"]),
				"quote_text" => "Originally posted by: ",
			);
			$this->smarty->assign("quote_data", $quote_data);
		}
		$thread = $this->helper->getThread($_REQUEST["record"]);
		$this->smarty->assign("record", $_REQUEST["record"]);
		$this->smarty->assign("thread_name", $thread["subject"]);
		$this->smarty->assign("nonce", wp_create_nonce("wpforum_insert_nonce"));
		return $this->smarty->fetch($this->template_dir . "/new_post_form.tpl");
	}

	function assignTrail()
	{
		$this->smarty->assign("trail", $this->helper->getTrail($this->action, $this->record));
	}

	public function assignButtons()
	{
		$current_user_id = get_current_user_id();
		if (is_user_logged_in()) {
			$nonce = wp_create_nonce("wpforum_ajax_nonce");
			$buttons = array(
				AppBase::FORUM_VIEW_ACTION => array(
					"new_thread" => "<a data-forum-id='" . $this->record . "' class='forum-button new_thread' href='" . ForumHelper::getLink(AppBase::NEW_THREAD_VIEW_ACTION, $this->record) . "'>Start Topic</a>",
				),
				AppBase::THREAD_VIEW_ACTION => array(
					"new_post" => "<a data-thread-id='" . $this->record . "' class='forum-button new_post' href='" . ForumHelper::getLink(AppBase::NEW_POST_VIEW_ACTION, $this->record) . "'>Reply</a>",
					//"subscribe_rss" => "<a class='forum-button subscribe_rss' href='" . ForumHelper::getLink(AppBase::RSS_POST_ACTION, $this->record) . "'>RSS Feed</a>",
					//"subscribe_email" => "<a class='forum-button subscribe_email' href='" . ForumHelper::getLink(AppBase::EMAIL_POST_ACTION, $this->record) . "'>Email Subscription</a>"
				),
			);

			switch ($this->action) {
				case AppBase::THREAD_VIEW_ACTION:
					$thread = $this->helper->getThread($this->record);
					if ($current_user_id == $thread["user_id"]) {
						if ($thread["is_question"] && !$thread["is_solved"]) {
							$buttons[$this->action]["mark_solved"] = "<a data-nonce='$nonce' data-thread-id='$this->record' id='marksolved' href='" . ForumHelper::getLink(AppBase::MARK_SOLVED_ACTION, $this->record) . "'>Mark solved</a>";
						}
					}
					break;
			}
			$this->smarty->assign("buttons", $buttons[$this->action]);
		} else {
			$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->smarty->assign("buttons", array(
				"login" => "<a href='" . wp_login_url($url) . "'>Login</a>",
				"signup" => "<a href='" . wp_registration_url() . "'>Sign Up</a>",
			));
		}
	}

	/*
	* @param
	* @return
	*/
	public function assignMisc()
	{
		$config = array(
			"date_format" => "%B %e, %Y",
			"number_format" => "0:'b':'hb'",
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
		$this->helper->updateThreadViewCount($this->record);
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
