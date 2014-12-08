<?php

require_once(ABSPATH . '/wp-load.php');
require_once("assets/bbcode.php");

/*
* Class:
* Author: Fredrik Fahlstad
*/

class ForumHelper
{
	public $db;
	protected static $_instance;

	public static function getInstance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
	}

	public static function input_filter($string)
	{
		global $wpdb;
		return strip_tags($wpdb->escape($string));
	}

	public static function markSolved($record)
	{
		global $wpdb;
		$sql = "UPDATE " . AppBase::$threads_table . " SET is_solved = '1' WHERE id = '$record'";
		$result = $wpdb->query($sql);
		return $result;
	}

	/*
	* @param
	* @return
	*/
	public
	static function getTotalPages($action)
	{
		global $wpdb;
		switch ($action) {
			case AppBase::FORUM_VIEW_ACTION:
				$per_page = AppBase::THREAD_PAGE_COUNT;
				$table = AppBase::$threads_table;
				break;
			case AppBase::THREAD_VIEW_ACTION:
				$per_page = AppBase::POST_PAGE_COUNT;
				$table = AppBase::$posts_table;
				break;
			default:
				return 1;
		}

		$sql = "SELECT count(*) FROM $table";
		$total_results = $wpdb->get_var($sql);
		$total_pages = ceil($total_results / $per_page);

		return $total_pages;
	}

	public function getTrail($action, $record)
	{
		/* Page > Forum -> Topic */
		$link_base = "<a href='%s'>%s</a>";

		$result = array(
			get_the_title()
		);


		switch ($action) {
			case AppBase::FORUM_VIEW_ACTION:
				/* BASE -> CATEGORY -> FORUM */
				$forum = $this->getForum($record);
				$category = $this->getCategory($forum["parent_id"]);
				$result[] = sprintf($link_base, get_permalink(), $category["name"]);
				$result[] = $forum["name"];
				break;
			case AppBase::THREAD_VIEW_ACTION:
				/* BASE -> CATEGORY -> FORUM -> THREAD */
				$thread = $this->getThread($record);
				$forum = $this->getForum($thread["parent_id"]);
				$category = $this->getCategory($forum["parent_id"]);
				$result[] = sprintf($link_base, get_permalink(), $category["name"]);
				$result[] = sprintf($link_base, self::getLink(AppBase::FORUM_VIEW_ACTION, $forum["id"]), $forum["name"]);
				$result[] = $thread["subject"];
				break;
			case AppBase::NEW_THREAD_VIEW_ACTION:
				/* BASE -> CATEGORY -> FORUM -> New Thread*/
				$forum = $this->getForum($record);
				$category = $this->getCategory($forum["parent_id"]);
				$result[] = sprintf($link_base, get_permalink(), $category["name"]);
				$result[] = $forum["name"];
				break;
			case AppBase::NEW_POST_VIEW_ACTION:
				/* BASE -> CATEGORY -> FORUM -> THREAD */
				$thread = $this->getThread($record);
				$forum = $this->getForum($thread["parent_id"]);
				$category = $this->getCategory($forum["parent_id"]);
				$result[] = sprintf($link_base, get_permalink(), $category["name"]);
				$result[] = sprintf($link_base, self::getLink(AppBase::FORUM_VIEW_ACTION, $forum["id"]), $forum["name"]);
				$result[] = $thread["subject"];
				break;
			default:
				break;
		}

		return implode(AppBase::TRAIL_SEPARATOR, $result);
	}

	/*
	* @param
	* @return
	*/
	public static function getLink($action, $record, $additional_params = "")
	{
		global $wp_rewrite;
		$delim = ($wp_rewrite->using_permalinks()) ? "?" : "&";

		$link_base = array(
			AppBase::APP_ACTION => $action,
			AppBase::RECORD => $record,
		);

		if (is_array($additional_params)) {
			$link_base[$additional_params[0]] = $additional_params[1];
		}

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
			$post["text"] = $this->outPutFilter($post["text"]);
			$post["avatar"] = get_avatar($post["user_id"], 65);
			$post["user"] = $this->getUserDataFiltered($post["user_id"]);
			$post["post_links"] = array(
				"quote" => array(
					"href" => ForumHelper::getLink(AppBase::NEW_POST_VIEW_ACTION, $record, array(AppBase::FORUM_QUOTE, $post["id"])),
					"text" => "Reply With Quote",
				),
			);
		}
		$thread = $this->getThread($record);
		$subject = $thread["subject"];
		$posts["header"] = $subject;
		$posts["prefix"] = $this->getThreadPrefix($thread);

		return $posts;
	}

	function outPutFilter($string)
	{
		return stripslashes(PP_BBCode($string));
	}

	/*
	* @param
	* @return
	*/
	public
	function getCategories()
	{
		$sql = "SELECT * FROM " . AppBase::$categories_table . " order by name";
		$categories = $this->db->get_results($sql, ARRAY_A);

		foreach ($categories as &$category) {
			foreach ($this->getForumsInCategory($category["id"]) as $forum) {
				$forum["href"] = self::getLink(AppBase::FORUM_VIEW_ACTION, $forum["id"]);//sprintf($this->forum_link_base, $forum["id"]);
				$category["forums"][] = $forum;
			}
		}

		return $categories;
	}

	/*
	* @param
	* @return
	*/
	public
	function getForumsInCategory($category_id)
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
	public
	function getThreadsInForum($forum_id, $offset)
	{
		$limit_query = "LIMIT $offset," . AppBase::THREAD_PAGE_COUNT;

		$sql = "select t.*, count(distinct(p.id)) as post_count, max(p.date) as last_post from " . AppBase::$threads_table . " t
			left join " . AppBase::$posts_table . " p on t.id = p.parent_id
				where t.parent_id = '$forum_id'
			group by t.id order by (status = 'sticky') DESC, last_post DESC $limit_query ";
		$threads = $this->db->get_results($sql, ARRAY_A);

		foreach ($threads as &$thread) {
			$thread["href"] = self::getLink(AppBase::THREAD_VIEW_ACTION, $thread["id"]);
			$thread["icon"] = self::getIcon($thread);
			$thread["user"] = $this->getUserDataFiltered($thread["user_id"]);
			$thread["last_poster"] = $this->lastPoster($thread["id"]);
			$thread["last_poster"]["avatar"] = get_avatar($thread["last_poster"]["user_email"], 22);
			$thread["prefix"] = $this->getThreadPrefix($thread);
		}
		return $threads;
	}

	function getThreadPrefix(array $thread)
	{
		$prefix = "";
		if ($thread["is_solved"]) {
			$prefix = "<span class='forum-solved-prefix'>[Solved]</span> ";
		}
		if ($thread["status"] == "sticky") {
			$prefix = "<span class='forum-sticky-prefix'>Sticky</span> ";
		}
		if ($thread["status"] == "closed") {
			$prefix = "<span class='forum-closed-prefix'>[Closed]</span> ";
		}
		return $prefix;
	}


	public
	function getPostText($id)
	{
		$sql = "select text from " . AppBase::$posts_table . " where id = '{$id}'";
		return $this->db->get_row($sql, ARRAY_A);
	}

	public
	function lastPoster($thread_id)
	{
		$sql = "select u.display_name, u.ID, u.user_email from " . AppBase::$users_table . " u LEFT JOIN  " . AppBase::$posts_table . " p on u.id = p.user_id where parent_id = '{$thread_id}' order by date DESC limit 1";
		return $this->db->get_row($sql, ARRAY_A);
	}

	public
	function getCategory($id)
	{
		$sql = "select name, id from " . AppBase::$categories_table . " where id = '{$id}'";
		return $this->db->get_row($sql, ARRAY_A);
	}

	public
	function getForum($id)
	{
		$sql = "select * from " . AppBase::$forums_table . " where id = '{$id}'";
		return $this->db->get_row($sql, ARRAY_A);
	}

	public
	function getThread($id)
	{
		$sql = "select * from " . AppBase::$threads_table . " where id = '{$id}'";
		return $this->db->get_row($sql, ARRAY_A);
	}

	public
	function getPost($id)
	{
		$sql = "select * from " . AppBase::$posts_table . " where id = '{$id}'";
		return $this->db->get_row($sql, ARRAY_A);
	}

	/*
	* @param
	* @return
	*/
	public
	function getIcon($thread)
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
				if ($thread["status"] == "open")
					return "thread-open";
				if ($thread["status"] == "closed")
					return "thread-closed";
				break;
			default:
				return "thread-open";
		}
	}

	function getUserDataFiltered($user_id)
	{
		static $user_post_count;

		$metas = array(
			"description",
		);
		$user = get_userdata($user_id)->data;
		foreach ($metas as $meta) {
			$user->meta[$meta] = get_user_meta($user_id, $meta, true);
		}

		if (!is_array($user_post_count) or !array_key_exists($user_id, $user_post_count)) {
			$user->post_count = $this->getUserPostCount($user_id);
			$user_post_count[$user_id] = $user->post_count;
		} else {
			$user->post_count = $user_post_count[$user_id];
		}
		return $user;
	}

	function getUserPostCount($user_id)
	{
		$sql = "SELECT count(*) from " . AppBase::$posts_table . " WHERE user_id = '$user_id'";
		return $this->db->get_var($sql);
	}

}


?>