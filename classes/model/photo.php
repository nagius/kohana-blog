<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Blog photo model
 *
 * @package 	Blog
 * @category    Model
 * @author 		Nicolas AGIUS
 * @copyright	(c) 2011 Nicolas AGIUS
 * @license 	MIT
 */
class Model_Photo extends Sprig
	implements Acl_Resource_Interface {

	/**
	 * Set the fields for the blog article
	 */
	protected function _init() {
		$this->_fields += array(
			'id'         => new Sprig_Field_Auto,
			// Metadata
			'title' => new Sprig_Field_Char(array(
				'max_length' => 255,
			)),
			'subtitle' => new Sprig_Field_Char(array(
				'max_length' => 255,
			)),
			'filename'   => new Sprig_Field_Image(array(
				'width' => 1000,
				'height' => 600,
				'directory' => Kohana::config('blog.upload.folder'),
			)),
			'path' => new Sprig_Field_Char(array(
				'editable' => FALSE,
				'in_db' => FALSE,
			)),
			// Relationships
			'article'   => new Sprig_Field_BelongsTo(array(
				'model'  => 'article',
			)),
			/* To be implemented
			'author'     => new Sprig_Field_BelongsTo(array(
				'model'    => 'user',
				'column'   => 'author_id',
				'editable' => FALSE,
			)),
			'tags'       => new Sprig_Field_ManyToMany(array(
				'model'  => 'tag',
			)),*/
		);
	}

	/**
	 * Overload Sprig::__get() to get
	 * full path
	 */
	public function __get($name) {
		if ($name == 'path')
		{
			return Kohana::config('blog.upload.folder').'/'.$this->filename;
		}
		else
		{
			return parent::__get($name);
		}
	} 

	/**
	 * Overload Sprig::delete() to remove 
	 * file from the upload dir
	 */
	public function delete(Database_Query_Builder_Delete $query = NULL) {
		Kohana::$log->add(Kohana::DEBUG, 'Beginning photo deletion');
		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('photo', 'delete photo');
		}

		if(file_exists($this->path))
			unlink($this->path);

		if (isset($benchmark))
		{
			Profiler::stop($benchmark);
		}

		return parent::delete($query);
	}

	/**
	 * Acl_Resource_Interface implementation of get_resource_id
	 *
	 * @return  string
	 */
	public function get_resource_id() {
		return 'photo';
	}

}

