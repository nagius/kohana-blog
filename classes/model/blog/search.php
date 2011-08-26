<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Blog article search model
 *
 * @package     Blog
 * @category    Model
 * @author      Kyle Treubig
 * @copyright   (c) 2010 Kyle Treubig
 * @license     MIT
 */
class Model_Blog_Search extends Sprig {

	/**
	 * @var Pagination article pagination
	 */
	public $pagination;

	/**
	 * Setup Sprig fields
	 */
	protected function _init() {
		$this->_fields += array(
			'query' => new Sprig_Field_Char(array(
				'in_db' => FALSE,
			)),
			'limit' => new Sprig_Field_Integer(array(
				'in_db' => FALSE,
				'default' => 10,	// TODO read this from config
			)),
			'total' => new Sprig_Field_Integer(array(
				'in_db' => FALSE,
			)),
		);

		$this->pagination = Pagination::factory();
	}

	/**
	 * Overload Sprig::load to default article sorting
	 *
	 * @param   Database_Query_Builder_Select select query
	 * @param   int return limit
	 * @return  Model_Article
	 */
	public function load(Database_Query_Builder_Select $query = NULL, $limit = NULL) {
		if ( ! $query)
		{
			$query = DB::select()->order_by('id', 'DESC')
				->order_by('date', 'DESC');
		}

		return Sprig::factory('article')->load($query, $limit);
	}

	/**
	 * Load a specific article by date
	 *
	 * @param   string  published date
	 * @return  Model_Article
	 */
	public function load_by_date($date) {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::load_by_date');

		list($year, $month, $day) = explode('/', $date, 3);
		$begin = strtotime($year.'-'.$month.'-'.$day);
		$end   = strtotime('+1 day', $begin);

		$query = DB::select()
			->where('date', '>=', $begin)
			->where('date', '<', $end);

		return $this->load($query, 1);
	}

	/**
	 * Load a specific article by slug
	 *
	 * @param   string  article slug
	 * @return  Model_Article
	 */
	public function load_by_slug($slug) {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::load_by_slug');

		return Sprig::factory('article', array('slug' => $slug))
			->load();
	}

	/**
	 * Load a single article by slug or date
	 *
	 * @param   string  article slug
	 * @param   string  published date
	 * @return  Model_Article
	 * @return  boolean false if no article found
	 */
	public function load_article($slug, $date) {
		$article = $this->load_by_slug($slug);
		if ( ! $article->loaded())
		{
			$article = $this->load_by_date($date);
		}
		return $article->loaded() ? $article : FALSE;
	}

	/**
	 * Load articles by category
	 *
	 * @param   string  article category
	 * @param   string  [optional] article state
	 * @return  Model_Article collection
	 */
	public function search_by_category($category, $state = 'published') {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::search_by_category');

		$category = Sprig::factory('category', array(
			'name' => $category))->load();

		if ( ! $category->loaded())
			throw new Kohana_Exception('Unknown category supplied.', NULL, 404);

		$query = DB::select()->where('state', '=', $state)
			->where('subcategory_id', 'IN', 
				DB::select('id')
					->from('subcategories')
					->where('category_id', '=', $category->id)
			)
			->order_by('id', 'DESC')
			->order_by('date', 'DESC');
		$limit = $this->limit;

		$this->total = DB::select(DB::expr('COUNT(*) AS count'))
			->from('articles')
			->where('state', '=', $state)
			->where('subcategory_id', 'IN', 
				DB::select('id')
					->from('subcategories')
					->where('category_id', '=', $category->id)
			)
			->execute()->get('count');

		$this->pagination->setup(array(
			'total_items'    => $this->total,
			'items_per_page' => $limit,
		));
		$query->offset($this->pagination->offset);

		return $this->load($query, $limit);
	}

	/**
	 * Load articles by subcategory
	 *
	 * @param   string  article subcategory
	 * @param   string  [optional] article state
	 * @return  Model_Article collection
	 */
	public function search_by_subcategory($subcategory, $state = 'published') {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::search_by_subcategory');

		$subcategory = Sprig::factory('subcategory', array(
			'name' => $subcategory))->load();

		if ( ! $subcategory->loaded())
			throw new Kohana_Exception('Unknown subcategory supplied.', NULL, 404);

		$query = DB::select()->where('state', '=', $state)
			->where('subcategory_id', '=', $subcategory->id)
			->order_by('id', 'DESC')
			->order_by('date', 'DESC');
		$limit = $this->limit;

		$this->total = DB::select(DB::expr('COUNT(*) AS count'))
			->from('articles')
			->where('state', '=', $state)
			->where('subcategory_id', '=', $subcategory->id)
			->execute()->get('count');

		$this->pagination->setup(array(
			'total_items'    => $this->total,
			'items_per_page' => $limit,
		));
		$query->offset($this->pagination->offset);

		return $this->load($query, $limit);
	}

	/**
	 * Load articles by title
	 *
	 * @param   string  article title
	 * @param   string  [optional] article state
	 * @return  Model_Article collection
	 */
	public function search_by_title($title, $state = 'published') {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::search_by_title');

		$query = DB::select()
			->where('title', 'like', "%".$title."%")
			->order_by('id', 'DESC')
			->order_by('date', 'DESC');

		$limit = $this->limit;

		$total = DB::select(DB::expr('COUNT(*) AS count'))
			->from('articles')
			->where('state', '=', $state)
			->where('title', 'like', "%$title%");

		if ($state != 'all')
		{
			$query->where('state', '=', $state);
			$total->where('state', '=', $state);
		}

		$this->total=$total->execute()->get('count');

		$this->pagination->setup(array(
			'total_items'    => $this->total,
			'items_per_page' => $limit,
		));
		$query->offset($this->pagination->offset);

		return $this->load($query, $limit);
	}

	/**
	 * Load articles by date
	 *
	 * @param   int year
	 * @param   int month
	 * @return  Model_Article collection
	 */
	public function search_by_date($date, $state = 'published') {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::search_by_date');

		// Get date ranges
		$data = explode('/', $date, 2);
		if (isset($data[1]) AND ! empty($data[1]))
		{
			$begin = strtotime($data[0].'-'.$data[1].'-01');
			$end   = strtotime('+1 month', $begin);
		}
		else
		{
			$begin = strtotime($data[0].'-01-01');
			$end   = strtotime('+1 year', $begin);
		}

		$query = DB::select()->where('state', '=', $state)
			->where('date', '>=', $begin)
			->where('date', '<', $end)
			->order_by('id', 'DESC')
			->order_by('date', 'DESC');
		$limit = $this->limit;

		$this->total = DB::select(DB::expr('COUNT(*) AS count'))
			->from('articles')
			->where('state', '=', $state)
			->where('date', '>=', $begin)
			->where('date', '<', $end)
			->execute()->get('count');

		$this->pagination->setup(array(
			'total_items'    => $this->total,
			'items_per_page' => $limit,
		));
		$query->offset($this->pagination->offset);

		return $this->load($query, $limit);
	}

	/**
	 * Load articles by state
	 *
	 * @param   string  [optional] article state, defaults to 'all'
	 * @return  Model_Article collection
	 */
	public function search_by_state($state = 'all') {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::search_by_state');

		$query = DB::select()
			->order_by('id', 'DESC')
			->order_by('date', 'DESC');
		$total= DB::select(DB::expr('COUNT(*) AS count'))
			->from('articles');
		$limit = $this->limit;

		if ($state != 'all')
		{
			$query->where('state', '=', $state);
			$total->where('state', '=', $state);
		}

		$this->total = $total->execute()->get('count');

		$this->pagination->setup(array(
			'total_items'    => $this->total,
			'items_per_page' => $limit,
		));
		$query->offset($this->pagination->offset);

		return $this->load($query, $limit);
	}

	/**
	 * Load articles by tag
	 *
	 * @param   string  article tag
	 * @param   string  [optional] article state
	 * @return  Model_Article collection
	 */
	public function search_by_tag($tag, $state = 'published') {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::search_by_tag');

		$tag = Sprig::factory('tag', array(
			'name' => $tag))->load();

		if ( ! $tag->loaded())
			throw new Kohana_Exception('Unknown tag supplied.', NULL, 404);

		$field = $tag->field('articles');
		$model = Sprig::factory('article');

		$query = DB::select()->where('state', '=', $state)
			->join($field->through)
			->on($model->fk($field->through), '=', $model->pk(TRUE))
			->where($tag->fk($field->through), '=', $tag->{$tag->_primary_key})
			->order_by('id', 'DESC')
			->order_by('date', 'DESC');
		$limit = $this->limit;

		$this->total = DB::select(DB::expr('COUNT(*) AS count'))
			->from($field->through)
			->where('tag_id', '=', $tag->id)
			->execute()->get('count');

		$this->pagination->setup(array(
			'total_items'    => $this->total,
			'items_per_page' => $limit,
		));
		$query->offset($this->pagination->offset);

		return $this->load($query, $limit);
	}

	/**
	 * Load articles by multi criteria
	 *
	 * @param   string  article tag
	 * @param   string  [optional] article state
	 * @return  Model_Article collection
	 */
	public function search_by_criteria($keywords, $criteria, $tags, $datemin, $datemax, $state = 'published') {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::search_by_criteria');

		$query = DB::select();

		// Search by text input with criteria
		if(isset($criteria))
		{
			$query->where_open();
			foreach($criteria as $criterion)
				$query->or_where($criterion, 'like', "%".$keywords."%");
			$query->where_close();
		}

		// Search by tags
		if(isset($tags))
		{
			$sub_query= DB::select('article_id')->distinct(TRUE)
				->from('articles_tags')
				->join('tags')->on('tags.id', '=', 'articles_tags.tag_id');
			foreach($tags as $tag)
				$sub_query->or_where('tags.name', 'like', $tag);
			$query->join(array($sub_query, 'subquery'))->on('articles.id', '=', 'subquery.article_id');
		}

		// Search by date (could be anything that strtotime() understand)
		if(isset($datemin) && strlen($datemin)>0)
		{
			$query->where('DATE(FROM_UNIXTIME("articles.date"))', '>=', date("Y-m-d", strtotime($datemin)));
		}
		if(isset($datemax) && strlen($datemax)>0)
		{
			$query->where('DATE(FROM_UNIXTIME("articles.date"))', '<=', date("Y-m-d", strtotime($datemax)));
		}

		// Search by state
		if ($state != 'all')
		{
			$query->where('state', '=', $state);
		}

		// Create a clone query to get the total number of records
		$total = clone $query;
		$total->select(array('COUNT("*")', 'count'))->from('articles');
		$this->total=$total->execute()->get('count');

		// Set the final sort 
		$query->order_by('id', 'DESC')->order_by('date', 'DESC');

		// Set pagination stuff
		$limit = $this->limit;
		$this->pagination->setup(array(
			'total_items'    => $this->total,
			'items_per_page' => $limit,
		));
		$query->offset($this->pagination->offset);

		return $this->load($query, $limit);

	}

	/**
	 * Get the top recent articles
	 *
	 * @param   int number of articles to load
	 * @param   string  [optional] article state
	 * @return  Model_Article collection
	 */
	public function get_recent_articles($limit = 5, $state = 'published') {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::get_recent_articles');

		$query = DB::select()->where('state', '=', $state)
			->order_by('date', 'DESC');

		return $this->load($query, $limit);
	}

	/**
	 * Get the most popular articles of this week
	 *
	 * @param   int number of articles to load
	 * @param   string  [optional] article state
	 * @return  Model_Article collection
	 */
	public function get_popular_articles($limit = 5, $state = 'published') {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::get_popular_articles');

		$stat    = Sprig::factory('statistic');
		$s_table = $stat->table();
		$article = Sprig::factory('article');
		$a_table = $article->table();

		$query = DB::select()
			->join($s_table)
			->on($article->fk($s_table), '=', $article->pk(TRUE))
			->where($a_table.'.state', '=', $state)
			->order_by($s_table.'.views', 'DESC');

		return $this->load($query, $limit);
	}

	/**
	 * Get the top recent comments
	 *
	 * @todo    Move this logic to comments module
	 *
	 * @param   int number of comments to load
	 * @return  Model_Blog_Comment collection
	 */
	public function get_recent_comments($limit = 5) {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Model_Blog_Search::get_recent_comments');

		$query = DB::select()->order_by('date', 'DESC');
		return Sprig::factory('blog_comment')->load($query, $limit);
	}

}	// End of Model_Blog_Search

