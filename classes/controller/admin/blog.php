<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Blog main management controller
 *
 * @package     Admin
 * @category    Controller
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
class Controller_Admin_Blog extends Controller_Admin {

	protected $_resource = 'article';

	protected $_acl_map = array(
		'default' => 'manage',
	);

	protected $_acl_required = 'all';

	protected $_view_map = array(
		'default' => 'admin/layout/narrow_column_with_menu',
	);

	protected $_view_menu_map = array();

	protected $_current_nav = 'admin/blog';

	/**
	 * Generate menu for blog management
	 */
	protected function _menu() {
		return View::factory('blog/admin/menu/default');
	}

	/**
	 * Blog management dashboard, display blog statistics
	 */
	public function action_index() {
		$this->template->content = View::factory('blog/admin/stats')
			->bind('stats', $stats);

		$categories = Sprig::factory('category')->load(NULL, FALSE);
		$tags       = Sprig::factory('tag')->load(NULL, FALSE);
		$articles   = Sprig::factory('article')->load(NULL, FALSE);
		$comments   = Sprig::factory('blog_comment')->load(NULL, FALSE);

		$stats = array();
		$stats['categories']['total'] = count($categories);
		$stats['tags']['total']       = count($tags);
		$stats['articles']['total']   = count($articles);
		$stats['comments']['total']   = count($comments);
	}

}	// End of Controller_Admin_Blog

