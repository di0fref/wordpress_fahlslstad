<?php
require_once("ForumHelper.php");
require_once("ForumView.php");
require_once("assets/guid.php");

if (!defined('WP_CONTENT_DIR')) define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
define('WPFDIR', dirname(plugin_basename(__FILE__)));
define('WPFPATH', WP_CONTENT_DIR . '/plugins/' . WPFDIR . '/');
define('WPFURL', WP_CONTENT_URL . '/plugins/' . WPFDIR . '/');

/*
* Class:
* Author: Fredrik Fahlstad
*/

class AppBase
{
	const CATEGORIES = "wpforum_categories";
	const FORUMS = "wpforum_forums";
	const THREADS = "wpforum_threads";
	const POSTS = "wpforum_posts";
	const USERS = "users";

	const FORUM_VIEW_ACTION = "viewforum";
	const THREAD_VIEW_ACTION = "viewthread";
	const NEW_THREAD_VIEW_ACTION = "newthread";
	const NEW_POST_VIEW_ACTION = "newpost";
	const RSS_POST_ACTION = "rss_feed";
	const EMAIL_POST_ACTION = "email_sub";
	const MARK_SOLVED_ACTION = "marksolved";

	const RECORD = "record";
	const APP_ACTION = "action";

	const THREAD_PAGE_COUNT = 5;
	const POST_PAGE_COUNT = 5;
	const FORUM_PAGE = "page";
	const FORUM_QUOTE = "quote";

	const TRAIL_SEPARATOR = " &rarr; ";
	const WPFORUM_INSERT_NONCE = "wpforum_insert_nonce";

	/* Options */
	const OPTION_DATE_FORMAT = "wpforum_option_date_format";
	const OPTION_THREADS_VIEW_COUNT = "wpforum_option_threads_view_count";
	const OPTION_POSTS_VIEW_COUNT = "wpforum_option_posts_view_count";

	static $border = 0;

	static $categories_table;
	static $forums_table;
	static $threads_table;
	static $posts_table;
	static $users_table;

	protected $action;
	protected $record;
	protected $page;

	public function __construct()
	{
		global $table_prefix;

		self::$categories_table = $table_prefix . self::CATEGORIES;
		self::$forums_table = $table_prefix . self::FORUMS;
		self::$threads_table = $table_prefix . self::THREADS;
		self::$posts_table = $table_prefix . self::POSTS;
		self::$users_table = $table_prefix . self::USERS;
	}

	public static $defined_actions = array(
		self::FORUM_VIEW_ACTION,
		self::THREAD_VIEW_ACTION,
		self::NEW_THREAD_VIEW_ACTION,
		self::NEW_POST_VIEW_ACTION,
		self::RSS_POST_ACTION,
		self::EMAIL_POST_ACTION,
		self::MARK_SOLVED_ACTION
	);

	function init()
	{
		add_option(self::OPTION_DATE_FORMAT, "%B %e, %Y");
		add_option(self::OPTION_THREADS_VIEW_COUNT, 20);
		add_option(self::OPTION_POSTS_VIEW_COUNT . 20);

	}

	public function main($content)
	{
		if (!preg_match('|<!--WPFORUM3-->|', $content))
			return $content;

		$offset = "";

		if (isset($_REQUEST[self::APP_ACTION])) {
			$this->action = $_REQUEST[self::APP_ACTION];
		}
		if (isset($_REQUEST[self::RECORD])) {
			$this->record = $_REQUEST[self::RECORD];
			self::checkParams($this->record, "guid");
		}
		if (isset($_REQUEST[self::FORUM_PAGE])) {
			$this->page = $_REQUEST[self::FORUM_PAGE];
			self::checkParams($this->page);
		}
		if (isset($_REQUEST[self::FORUM_QUOTE])) {
			self::checkParams($_REQUEST[self::FORUM_QUOTE], "guid");
		}
		$offset = $this->calculateOffset();
		$view = new ForumView($this->action, $this->record, $offset);

		switch ($this->action) {
			case self::FORUM_VIEW_ACTION:
				$data = $view->getForumView();
				break;
			case self::THREAD_VIEW_ACTION:
				$data = $view->getTopicView();
				break;
			case self::NEW_THREAD_VIEW_ACTION:
				$data = $view->getNewThreadView();
				break;
			case self::NEW_POST_VIEW_ACTION:
				$data = $view->getNewPostView();
				break;
			default:
				$data = $view->getMainView();
				break;
		}

		$header = $this->getHeader();
		$footer = $this->getFooter();

		$out = "<div id='forum-wrapper'>" . $header . $data . $footer . "</div>";

		return preg_replace('|<!--WPFORUM3-->|', $out, $content);

	}

	/*
	* @param
	* @return
	*/
	public function calculateOffset()
	{
		switch ($this->action) {
			case AppBase::FORUM_VIEW_ACTION:
				$count = AppBase::THREAD_PAGE_COUNT;
				break;
			case AppBase::THREAD_VIEW_ACTION:
				$count = AppBase::POST_PAGE_COUNT;
				break;
			default:
				$count = 0;
		}
		if ($this->page == 1 or empty($this->page)) {
			$start = 0;
		} else {
			$start = ($this->page-1) * $count;
		}
		return $start;
	}

	public function getHeader()
	{
	}

	public function getFooter()
	{
		$out = "";
		if (!empty($this->action)) {
			$out .= '<div class="pagination"><ul>';
			$out .= paginate("?page_id=6&action=" . $this->action . "&record={$this->record}", $this->page, ForumHelper::getTotalPages($this->action, $this->record));
			$out .= "</ul></div>";
		}
		$out .= '<div id="forum-dialog" title="Dialog">';
		return $out;
	}

	public static function checkParams($parm, $type = "")
	{
		switch ($type) {
			case "guid":
				if (!is_guid($parm) and !is_numeric($parm)) {
					wp_die("Bad request, please re-enter 1.");
				}
				return true;
				break;
		}

		$regexp = "/^([+-]?((([0-9]+(\.)?)|([0-9]*\.[0-9]+))([eE][+-]?[0-9]+)?))$/";
		if (!preg_match($regexp, $parm))
			wp_die("Bad request, please re-enter 2.");
	}


	/*
	* @param
	* @return
	*/
	public function install()
	{

		$categories_sql = "
			CREATE TABLE IF NOT EXISTS " . self::$categories_table . " (
			  id varchar(36) NOT NULL default '',
			  `name` varchar(255) NOT NULL default '',
			  `description` varchar(255) default '',
			  PRIMARY KEY  (id)
			);";

		$forums_sql = "
			CREATE TABLE IF NOT EXISTS " . self::$forums_table . " (
			  id varchar(36) NOT NULL default '',
			  `name` varchar(255) NOT NULL default '',
			  parent_id varchar(36) NOT NULL default '',
			  description varchar(255) NOT NULL default '',
			  PRIMARY KEY  (id),
			  INDEX parent_idx (parent_id)
			);";

		$threads_sql = "
			CREATE TABLE IF NOT EXISTS " . self::$threads_table . " (
			  id varchar(36) NOT NULL default '',
			  parent_id varchar(36) NOT NULL default '',
			  views int(11) NOT NULL default '0',
			  `subject` varchar(255) NOT NULL default '',
			  `date` datetime NOT NULL default '0000-00-00 00:00:00',
			  `status` varchar(20) NOT NULL default 'open',
			  is_question bool default 0,
			  is_solved bool default 0,
			  user_id int(11) NOT NULL,
			  PRIMARY KEY  (id),
			  INDEX parent_idx (parent_id),
			  INDEX user_idx (user_id)
			);";

		$posts_sql = "
			CREATE TABLE IF NOT EXISTS " . self::$posts_table . " (
			  id varchar(36) NOT NULL default '',
			  `text` longtext,
			  parent_id varchar(36) NOT NULL default '',
			  `date` datetime NOT NULL default '0000-00-00 00:00:00',
			  user_id int(11) NOT NULL default '0',
			  `subject` varchar(255) NOT NULL default '',
			  PRIMARY KEY  (id),
			  INDEX parent_idx (parent_id),
			  INDEX user_idx (user_id)
			);";

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		dbDelta($categories_sql);
		dbDelta($forums_sql);
		dbDelta($threads_sql);
		dbDelta($posts_sql);
	}

	/*
	* @param
	* @return
	*/
	public function head()
	{

	}

	/*
	* @param
	* @return
	*/
	public function enqueue_scripts()
	{
		wp_register_style('wpforum_styles', plugins_url('assets/styles/style.css', __FILE__), array(), '', 'all');
		wp_register_style('jquery_ui_styles', plugins_url('assets/js/jquery-ui/jquery-ui.min.css', __FILE__), array(), '', 'all');
		wp_register_style('wpforum_editor_styles', plugins_url('assets/js/markitup/sets/bbcode/style.css', __FILE__), array(), '', 'all');

		wp_enqueue_style('wpforum_styles');
		wp_enqueue_style('jquery_ui_styles');
		wp_enqueue_style('wpforum_editor_styles');

		wp_register_script('jquery_ui_js', plugins_url('assets/js/jquery-ui/jquery-ui.min.js', __FILE__), array("jquery"), '', false);
		wp_register_script('wpforum_script', plugins_url('assets/js/forum.js', __FILE__), array("jquery"), '', false);
		wp_register_script('wpforum_editor_js', plugins_url('assets/js/markitup/jquery.markitup.js', __FILE__), array("jquery"), '', false);
		wp_register_script('wpforum_editor_set_js', plugins_url('assets/js/markitup/sets/bbcode/set.js', __FILE__), array("jquery"), '', false);
		wp_register_script('jquery_validate_js', plugins_url('assets/js/jquery.validate.min.js', __FILE__), array("jquery"), '', false);

		wp_enqueue_script('wpforum_editor_js');
		wp_enqueue_script('wpforum_editor_set_js');
		wp_enqueue_script('wpforum_script');
		wp_enqueue_script('jquery_ui_js');
		wp_enqueue_script('jquery_validate_js');

		wp_localize_script('wpforum_script', 'forumAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
	}

	function processForm()
	{
		/* Processing forms */

		/* New thread */
		if (isset($_POST["forum-form-new-thread"])) {
			if (!is_user_logged_in()) {
				wp_die("No naughty business please");
			}
			self::verifyNonce(self::WPFORUM_INSERT_NONCE);
			include("AddThread.php");
			header("Location:" . $redirect_url);
			exit();
		}

		/* Post reply*/
		if (isset($_POST["forum-form-new-post"])) {
			if (!is_user_logged_in()) {
				wp_die("No naughty business please");
			}
			self::verifyNonce(self::WPFORUM_INSERT_NONCE);
			include("AddPost.php");
			header("Location:" . $redirect_url);
			exit();
		}
	}

	public static function verifyNonce($nonce)
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], $nonce)) {
			wp_die("No naughty business please");
		}
	}
}


function paginate($reload, $page, $tpages)
{
	if ($tpages > 1) {

		if (empty($page)) $page = 1;

		$adjacents = 4;
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = "";
		// previous
		if ($page == 1) {
			$out .= "<span style='white-space:nowrap'>" . $prevlabel . "</span>\n";
		} elseif ($page == 2) {
			$out .= "<li style='white-space:nowrap'><a  href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
		} else {
			$out .= "<li style='white-space:nowrap'><a  href=\"" . $reload . "&amp;page=" . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";
		}

		$pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
		$pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
		for ($i = $pmin; $i <= $pmax; $i++) {
			if ($i == $page) {
				$out .= "<li  class=\"active\"><a href=''>" . $i . "</a></li>\n";
			} elseif ($i == 1) {
				$out .= "<li><a  href=\"" . $reload . "\">" . $i . "</a>\n</li>";
			} else {
				$out .= "<li><a  href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a>\n</li>";
			}
		}

		if ($page < ($tpages - $adjacents)) {
			$out .= "<a style='font-size:11px' href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $tpages . "</a>\n";
		}
		// next
		if ($page < $tpages) {
			$out .= "<li style='white-space:nowrap'><a  href=\"" . $reload . "&amp;page=" . ($page + 1) . "\">" . $nextlabel . "</a>\n</li>";
		} else {
			$out .= "<span style='font-size:11px white-space:nowrap'>" . $nextlabel . "</span>\n";
		}
		$out .= "";
		return $out;
	}
}
