<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Blog subcategory model
 *
 * @package     Blog
 * @category    Model
 * @author      Nicolas AGIUS
 * @copyright   (c) 2011 LPS
 * @license     GPL
 */
class Model_Subcategory extends Sprig
	implements Acl_Resource_Interface {

	/**
	 * Setup model fields
	 */
	public function _init() {
		$this->_fields += array(
			'id'       => new Sprig_Field_Auto,
			'name'     => new Sprig_Field_Char,
			'articles' => new Sprig_Field_HasMany,
	        'category' => new Sprig_Field_BelongsTo(array(
                'model'  => 'category',
                'null'   => FALSE,
            )),
		);
	}

	/**
	 * Get all published articles belonging to this subcategory
	 */
	public function published(Database_Query_Builder_Select $query = NULL, $limit = 1) {
		return Sprig::factory('article', array(
			'state'    => 'published',
			'subcategory' => $this,
		))->load($query, $limit);
	}

	/**
	 * Overload Sprig::delete() to update child articles
	 * to become children of the uncategorized subcategory
	 */
	public function delete(Database_Query_Builder_Delete $query = NULL) {
		Kohana::$log->add(Kohana::DEBUG, 'Beginning subcategory deletion for subcategory_id='.$this->id);
		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('blog', 'delete subcategory');
		}

		$uncategorized = Sprig::factory('subcategory', array('name'=>'uncategorized'))->load();

		// Modify category IDs for all child articles
		try
		{
			DB::update('articles')->value('subcategory_id', $uncategorized->id)
				->where('subcategory_id', '=', $this->id)->execute();
		}
		catch (Database_Exception $e)
		{
			Kohana::$log->add(Kohana::ERROR, 'Exception occured while modifying deleted subcategory\'s articles. '.$e->getMessage());
			return $this;
		}

		if (isset($benchmark))
		{
			Profiler::stop($benchmark);
		}

		return parent::delete($query);
	}

	/**
	 * Acl_Resource_Interface implementation of get_resource_id
	 *
	 * If the current category is uncategorized, return a bogus
	 * resource to prevent deletion/modification
	 *
	 * @return  string
	 */
	public function get_resource_id() {
		if ($this->loaded() AND $this->name == 'uncategorized')
			return 'bogus_resource';
		else
			return 'subcategory';
	}

}

