<?php


class AppBase
{
	const CATEGORIES = "wpforum_categories";
	const FORUMS = "wpforum_forums";
	const THREADS = "wpforum_threads";
	const POSTS = "wpforum_posts";

	static $categories_table;
	static $forums_table;
	static $threads_table;
	static $posts_table;

	protected $action;
	protected $record;

	public function __construct()
	{
		global $table_prefix;

		self::$categories_table = $table_prefix . self::CATEGORIES;
		self::$forums_table = $table_prefix . self::FORUMS;
		self::$threads_table = $table_prefix . self::THREADS;
		self::$posts_table = $table_prefix . self::POSTS;
	}

	public static $defined_actions = array(
		"viewforum",
		"viewthread",
		"page"
	);

	public function main($content)
	{
		if (!preg_match('|<!--WPFORUM3-->|', $content))
			return $content;
		$data = "";
		//self::checkParams();
		if (isset($_REQUEST["action"])) {
			$this->action = $_REQUEST["action"];
		}
		if (isset($_REQUEST["record"])) {
			$this->record = $_REQUEST["record"];
		}

		switch ($this->action) {
			case "viewforum":
				$data = View::getInstance()->getForumView($this->action, $this->record);
				break;
			case "viewthread":
				$data = View::getInstance()->getTopicView($this->action, $this->record);
				break;
			default:
				$data = View::getInstance()->getMainView($this->action, $this->record);
				break;
		}

		return preg_replace('|<!--WPFORUM3-->|', $data, $content);

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
			  PRIMARY KEY  (id)
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
			  views int(11) NOT NULL,
			  user_id int(11) NOT NULL,
			  PRIMARY KEY  (id)
			);";

		$posts_sql = "
			CREATE TABLE IF NOT EXISTS " . self::$posts_table . " (
			  id int(11) NOT NULL auto_increment,
			  `text` longtext,
			  parent_id int(11) NOT NULL default '0',
			  `date` datetime NOT NULL default '0000-00-00 00:00:00',
			  user_id int(11) NOT NULL default '0',
			  `subject` varchar(255) NOT NULL default '',
			  PRIMARY KEY  (id)
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
		wp_register_style('wpforum_styles', plugins_url('style.css', __FILE__), array(), '20141203', 'all');
		wp_enqueue_style('wpforum_styles');
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
	protected static $_instance;
	protected $smarty;
	protected $template_dir;
	protected $helper;

	public function __construct()
	{
		$this->template_dir = WPFPATH . "/tpls";
		$this->smarty = new Smarty();
		$this->helper = new ForumHelper();
	}

	public static function getInstance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/*
	* @param
	* @return string
	*/
	public function getForumView($action, $record)
	{
		$this->smarty->assign("data", $this->helper->getThreadsInForum($record));
		return $this->smarty->fetch($this->template_dir . "/threads.tpl");
	}

	/*
	* @param $action string
	* @param $record string
	* @return string
	*/
	public function getTopicView($action, $record)
	{

	}

	/*
	* @param $action string
	* @param $record string
	* @return string
	*/
	public function getMainView($action, $record)
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
	protected $path;
	protected $forum_link_base;
	protected $thread_link_base;

	public function __construct()
	{
		$this->path = basename(get_permalink());
		$this->forum_link_base = $this->path . "&action=viewforum&record=%s";
		$this->thread_link_base = $this->path . "&action=viewthread&record=%s";
	}

	/*
	* @param
	* @return
	*/
	public function getCategories()
	{
		global $wpdb;
		$sql = "SELECT * FROM " . AppBase::$categories_table . " order by name";
		$categories = $wpdb->get_results($sql, ARRAY_A);

		foreach ($categories as &$category) {
			foreach ($this->getForumsInCategory($category["id"]) as $forum) {
				$forum["href"] = sprintf($this->forum_link_base, $forum["id"]);
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
		global $wpdb;

		$sql = "select f.id, f.name, f.description, max(p.date) as last_post, count(distinct(p.id)) as post_count, count(distinct(t.id)) as thread_count from " . AppBase::$forums_table . " f
					left join " . AppBase::$threads_table . " t on t.parent_id = f.id
						left join " . AppBase::$posts_table . " p on p.parent_id = t.id
						where f.parent_id = '{$category_id}'
				group by f.id;";
		$result = $wpdb->get_results($sql, ARRAY_A);
		return $result;
	}

	/*
	* @param
	* @return
	*/
	public function getThreadsInForum($forum_id)
	{
		global $wpdb;
		
		$sql = "select t.*, count(distinct(p.id)) as post_count, max(p.date) as last_post from " . AppBase::$threads_table . " t
			left join " . AppBase::$posts_table . " p on t.id = p.parent_id
				where t.parent_id = 2
			group by t.id;";
		$threads = $wpdb->get_results($sql, ARRAY_A);

		foreach($threads as &$thread){
			$thread["href"] = sprintf($this->thread_link_base, $thread["id"]);
		}
		return $threads;
	}
}