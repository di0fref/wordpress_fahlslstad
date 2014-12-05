<?php

class AppBase
{
	const CATEGORIES = "wpforum_categories";
	const FORUMS = "wpforum_forums";
	const THREADS = "wpforum_threads";
	const POSTS = "wpforum_posts";
	const USERS = "users";

	const FORUM_ACTION = "viewforum";
	const THREAD_ACTION = "viewthread";
	const NEW_THREAD_ACTION = "newthread";
	const NEW_POST_ACTION = "newpost";
	const RSS_POST_ACTION = "rss_feed";
	const EMAIL_POST_ACTION = "email_sub";

	const RECORD = "record";
	const APP_ACTION = "action";

	const THREAD_PAGE_COUNT = 20;
	const POST_PAGE_COUNT = 20;
	const FORUM_PAGE = "page";

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
		self::FORUM_ACTION,
		self::THREAD_ACTION,
		self::NEW_THREAD_ACTION,
		self::NEW_POST_ACTION,
		self::RSS_POST_ACTION,
		self::EMAIL_POST_ACTION,
	);

	public function main($content)
	{
		if (!preg_match('|<!--WPFORUM3-->|', $content))
			return $content;

		$offset = "";
		//self::checkParams();

		if (isset($_REQUEST[self::APP_ACTION])) {
			$this->action = $_REQUEST[self::APP_ACTION];
		}
		if (isset($_REQUEST[self::RECORD])) {
			$this->record = $_REQUEST[self::RECORD];
		}
		if (isset($_REQUEST[self::FORUM_PAGE])) {
			$this->page = $_REQUEST[self::FORUM_PAGE];
		}

		$offset = $this->calculateOffset();
		$view = new View($this->action, $this->record, $offset);

		switch ($this->action) {
			case self::FORUM_ACTION:
				$data = $view->getForumView();
				break;
			case self::THREAD_ACTION:
				$data = $view->getTopicView();
				break;
			default:
				$data = $view->getMainView();
				break;
		}

		$header = $this->getHeader();
		$footer = $this->getFooter();

		return preg_replace('|<!--WPFORUM3-->|', $header . $data . $footer, $content);

	}

	/*
	* @param
	* @return
	*/
	public function calculateOffset()
	{
		switch ($this->action) {
			case AppBase::FORUM_ACTION:
				$count = AppBase::THREAD_PAGE_COUNT;
				break;
			case AppBase::THREAD_ACTION:
				$count = AppBase::POST_PAGE_COUNT;
				break;
			default:
				$count = 0;
		}
		if ($this->page == 1 or empty($this->page)) {
			$start = 0;
		} else {
			$start = $this->page * $count;
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
			$out .= paginate("?page_id=6&action=" . $this->action . "&record={$this->record}", $this->page, ForumHelper::getTotalPages($this->action));
			$out .= "</ul></div>";
		}
		$out .= '<div id="forum-dialog" title="Dialog">';
		return $out;
	}

	public static function checkParams()
	{

		$error = false;

		if (isset($_REQUEST["action"])) {
			if (!in_array($_REQUEST["action"], self::$defined_actions)) {
				$error = true;
			}
		}
		if (isset($_REQUEST["record"])) {
			if (!is_numeric($_REQUEST["record"])) {
				$error = true;
			}
		}
		if ($error) {
			wp_die("Wrong parameters");
		}
	}


	/*
	* @param
	* @return
	*/
	public function install()
	{

		global $table_prefix;
		$categories_sql = "
			CREATE TABLE IF NOT EXISTS " . self::$categories_table . " (
			  id int(11) NOT NULL auto_increment,
			  `name` varchar(255) NOT NULL default '',
			  `description` varchar(255) default '',
			  PRIMARY KEY  (id)
			);";

		$forums_sql = "
			CREATE TABLE IF NOT EXISTS " . self::$forums_table . " (
			  id int(11) NOT NULL auto_increment,
			  `name` varchar(255) NOT NULL default '',
			  parent_id int(11) NOT NULL default '0',
			  description varchar(255) NOT NULL default '',
			  PRIMARY KEY  (id),
			  INDEX primary_idx (primary_id)
			);";

		$threads_sql = "
			CREATE TABLE IF NOT EXISTS " . self::$threads_table . " (
			  id int(11) NOT NULL auto_increment,
			  parent_id int(11) NOT NULL default '0',
			  views int(11) NOT NULL default '0',
			  `subject` varchar(255) NOT NULL default '',
			  `date` datetime NOT NULL default '0000-00-00 00:00:00',
			  `status` varchar(20) NOT NULL default 'open',
			  is_question bool default 0,
			  is_solved bool default 0,
			  views int(11) NOT NULL,
			  user_id int(11) NOT NULL,
			  PRIMARY KEY  (id),
			  INDEX primary_idx (primary_id),
			  INDEX user_idx (user_id)
			);";

		$posts_sql = "
			CREATE TABLE IF NOT EXISTS " . self::$posts_table . " (
			  id int(11) NOT NULL auto_increment,
			  `text` longtext,
			  parent_id int(11) NOT NULL default '0',
			  `date` datetime NOT NULL default '0000-00-00 00:00:00',
			  user_id int(11) NOT NULL default '0',
			  `subject` varchar(255) NOT NULL default '',
			  PRIMARY KEY  (id),
			  INDEX primary_idx (primary_id),
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
		wp_register_style('wpforum_styles', plugins_url('style.css', __FILE__), array(), '', 'all');
		wp_register_style('jquery-ui-styles', plugins_url('assets/js/jquery-ui/jquery-ui.min.css', __FILE__), array(), '', 'all');

		wp_enqueue_style('wpforum_styles');
		wp_enqueue_style('jquery-ui-styles');

		wp_register_script('jquery-ui-js', plugins_url('assets/js/jquery-ui/jquery-ui.min.js', __FILE__), array("jquery"), '', false);
		wp_register_script('wpforum_script', plugins_url('assets/js/forum.js', __FILE__), array("jquery"), '', false);

		wp_enqueue_script('wpforum_script');
		wp_enqueue_script('jquery-ui-js');
	}
}


require_once("assets/Smarty/libs/Smarty.class.php");

if (!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
define('WPFDIR', dirname(plugin_basename(__FILE__)));
define('WPFPATH', WP_CONTENT_DIR . '/plugins/' . WPFDIR . '/');

/*
* Class:
* Author: Fredrik Fahlstad
*/

class View
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

	/*
	* @param
	* @return
	*/
	public function assignButtons()
	{
		$buttons = array(
			AppBase::FORUM_ACTION => array(
				"new_thread" => "<a data-forum-id='" . $this->record . "' class='forum-button new_thread'	href='" . ForumHelper::getLink(AppBase::NEW_THREAD_ACTION, $this->record) . "'>Start Topic</a>",
			),
			AppBase::THREAD_ACTION => array(
				"new_post" => "<a data-thread-id='" . $this->record . "' class='forum-button new_post' href='" . ForumHelper::getLink(AppBase::NEW_POST_ACTION, $this->record) . "'>Reply</a>",
				"subscribe_rss" => "<a class='forum-button subscribe_rss' href='" . ForumHelper::getLink(AppBase::RSS_POST_ACTION, $this->record) . "'>RSS Feed</a>",
				"subscribe_email" => "<a class='forum-button subscribe_email' href='" . ForumHelper::getLink(AppBase::EMAIL_POST_ACTION, $this->record) . "'>Email Subscription</a>"
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

/*
* Class:
* Author: Fredrik Fahlstad
*/

class ForumHelper
{
	public $db;

	public function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
	}

	/*
	* @param
	* @return
	*/
	public static function getTotalPages($action)
	{
		global $wpdb;
		$per_page = "";
		$table = "";
		switch ($action) {
			case AppBase::FORUM_ACTION:
				$per_page = AppBase::THREAD_PAGE_COUNT;
				$table = AppBase::$threads_table;
				break;
			case AppBase::THREAD_ACTION:
				$per_page = AppBase::POST_PAGE_COUNT;
				$table = AppBase::$posts_table;
				break;
		}

		$sql = "SELECT count(*) FROM $table";
		$total_results = $wpdb->get_var($sql);
		$total_pages = ceil($total_results / $per_page);

		return $total_pages;
	}

	/*
	* @param
	* @return
	*/
	public static function getLink($action, $record)
	{
		global $wp_rewrite;
		$delim = ($wp_rewrite->using_permalinks()) ? "?" : "&amp;";

		switch ($action) {
			case AppBase::FORUM_ACTION:
				break;
			case AppBase::THREAD_ACTION:
				break;
		}

		$link_base = array(
			AppBase::APP_ACTION => $action,
			AppBase::RECORD => $record
		);
		return get_permalink() . $delim . http_build_query($link_base);
	}

	/*
	* @param
	* @return
	*/
	public function getPostsInThread($record, $offset)
	{
		$limit_query = "LIMIT $offset," . AppBase::POST_PAGE_COUNT;

		$sql = "SELECT p.*, t.subject as thread_subject FROM " . AppBase::$posts_table . " p left join " . AppBase::$threads_table . " t on t.id = p.parent_id WHERE p.parent_id='$record' order by date $limit_query";
		$posts["posts"] = $this->db->get_results($sql, ARRAY_A);
		foreach ($posts["posts"] as &$post) {
			$post["avatar"] = get_avatar($post["user_id"], 65);
			$user_data = get_userdata($post["user_id"]);
			$user_data->meta = get_user_meta($post["user_id"]);
			$post["user"] = $user_data;
			$post["user"]->post_count = $this->db->get_var("select count(*) from " . AppBase::$posts_table . " where user_id = '{$post["user_id"]}'");
		}
		$posts["header"] = $this->getThreadName($record);
		return $posts;
	}

	/*
	* @param
	* @return
	*/
	public function getCategories()
	{
		$sql = "SELECT * FROM " . AppBase::$categories_table . " order by name";
		$categories = $this->db->get_results($sql, ARRAY_A);

		foreach ($categories as &$category) {
			foreach ($this->getForumsInCategory($category["id"]) as $forum) {
				$forum["href"] = self::getLink(AppBase::FORUM_ACTION, $forum["id"]);//sprintf($this->forum_link_base, $forum["id"]);
				$category["forums"][] = $forum;
			}
		}

		return $categories;
	}

	/*
	* @param
	* @return
	*/
	public function getForumsInCategory($category_id)
	{
		$sql = "select f.id, f.name, f.description, max(p.date) as last_post, count(distinct(p.id)) as post_count, count(distinct(t.id)) as thread_count from " . AppBase::$forums_table . " f
					left join " . AppBase::$threads_table . " t on t.parent_id = f.id
						left join " . AppBase::$posts_table . " p on p.parent_id = t.id
						where f.parent_id = '{$category_id}'
				group by f.id;";
		$result = $this->db->get_results($sql, ARRAY_A);
		return $result;
	}

	/*
	* @param
	* @return
	*/
	public function getThreadsInForum($forum_id, $offset)
	{
		$limit_query = "LIMIT $offset," . AppBase::THREAD_PAGE_COUNT;

		$sql = "select t.*, count(distinct(p.id)) as post_count, max(p.date) as last_post from " . AppBase::$threads_table . " t
			left join " . AppBase::$posts_table . " p on t.id = p.parent_id
				where t.parent_id = '$forum_id'
			group by t.id order by (status = 'sticky') DESC, last_post DESC $limit_query ";
		$threads = $this->db->get_results($sql, ARRAY_A);

		foreach ($threads as &$thread) {
			$thread["href"] = self::getLink(AppBase::THREAD_ACTION, $thread["id"]);
			$thread["icon"] = self::getIcon($thread);
			$thread["user"] = get_userdata($thread["user_id"]);
			$thread["last_poster"] = $this->lastPoster($thread["id"]);
			$thread["last_poster"]["avatar"] = get_avatar($thread["user_id"], 22);
		}
		return $threads;
	}

	/*
	* @param
	* @return
	*/
	public function lastPoster($thread_id)
	{
		$sql = "select u.display_name, u.ID, u.user_email from " . AppBase::$users_table . " u LEFT JOIN  " . AppBase::$posts_table . " p on u.id = p.user_id where parent_id = '{$thread_id}' order by date DESC limit 1";
		return $this->db->get_row($sql, ARRAY_A);
	}

	/*
	* @param
	* @return
	*/
	public function getThreadName($id)
	{
		$sql = "select subject from " . AppBase::$threads_table . " where id = '{$id}'";
		return $this->db->get_var($sql);
	}

	/*
	* @param
	* @return
	*/
	public function getIcon($thread)
	{
		switch ($thread["is_question"]) {
			case "1":
				if ($thread["is_solved"]) {
					return "thread-solved";
				} else {
					return "thread-is-question";
				}
				break;
			case "0":
				if ($thread["status"] == "sticky")
					return "thread-sticky";
				else {
					return "thread-open";
				}
				break;
			default:
				return "thread-open";
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