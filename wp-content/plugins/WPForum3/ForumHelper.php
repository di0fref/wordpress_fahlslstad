<?php

require_once(ABSPATH . '/wp-load.php');

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

	public static function input_filter($string)
	{
		global $wpdb;
		return strip_tags($wpdb->escape($string));
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

	function getTrail($action)
	{
		/* Page > Forum -> Topic */

		$base = wp_title();

		switch($action){
			case AppBase::FORUM_VIEW_ACTION:
				/* BASE -> CATEGORY -> FORUM */
				break;
			case AppBase::THREAD_VIEW_ACTION:
				/* BASE -> CATEGORY -> FORUM -> THREAD */
				break;
			case AppBase::NEW_THREAD_VIEW_ACTION:
				/* BASE -> CATEGORY -> FORUM -> New Thread*/
				break;
			case AppBase::NEW_POST_VIEW_ACTION:
				/* BASE -> CATEGORY -> FORUM -> THREAD -> Post Reply */
				break;
		}
	}

	/*
	* @param
	* @return
	*/
	public static function getLink($action, $record)
	{
		global $wp_rewrite;
		$delim = ($wp_rewrite->using_permalinks()) ? "?" : "&";

		switch ($action) {
			case AppBase::FORUM_VIEW_ACTION:
				break;
			case AppBase::THREAD_VIEW_ACTION:
				break;
		}

		$link_base = array(
			AppBase::APP_ACTION => $action,
			AppBase::RECORD => $record,
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
			$thread["href"] = self::getLink(AppBase::THREAD_VIEW_ACTION, $thread["id"]);
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

?>