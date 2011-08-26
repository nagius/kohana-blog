<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Blog subcategory management controller
 *
 * @package     Admin
 * @category    Controller
 * @author      Nicolas AGIUS
 * @copyright   (c) 2011 LPS
 * @license     GPL
 */
class Controller_Admin_Blog_Subcategory extends Controller_Admin {

	protected $_resource = 'subcategory';

	protected $_acl_map = array(
		'new'     => 'create',
		'edit'    => 'edit',
		'delete'  => 'delete',
		'default' => 'manage',
	);

	protected $_acl_required = 'all';

	protected $_view_map = array(
		'list'    => 'admin/layout/wide_column_with_menu',
		'default' => 'admin/layout/narrow_column',
	);

    protected $_view_menu_map = array();

	protected $_resource_required = array('edit', 'delete');

	protected $_current_nav = 'admin/blog';

	/**
	 * Generate menu for blog management
	 */
	protected function _menu() {
		return View::factory('blog/admin/menu/default')
			->set('links', array(
				'Create Subcategory' => $this->request->uri(array('action'=>'new')),
			));
	}

	/**
	 * Load the specified subcategory
	 */
	protected function _load_resource() {
		$id = $this->request->param('id', 0);
		$this->_resource = Sprig::factory('subcategory', array('id'=>$id))->load();
		if ( ! $this->_resource->loaded())
			throw new Kohana_Exception('That subcategory does not exist.', NULL, 404);
	}

	/**
	 * Redirect index action to list
	 */
	public function action_index() {
		$this->request->redirect( $this->request->uri(
			array('action' => 'list')), 301);
	}

	/**
	 * Display list of subcategories
	 */
	public function action_list() {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Controller_Admin_Subcategory::action_list');
		$this->template->content = View::factory('blog/admin/subcategory/list')
			->set('tbody', View::factory('blog/admin/subcategory/list_tbody')
				->bind('request', $this->request)
				->bind('subcategories', $subcategories)
			);
		$subcategories = Sprig::factory('subcategory')->load(NULL, FALSE);
	}

	/**
	 * Create a new subcategory
	 */
	public function action_new() {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Controller_Admin_Subcategory::action_new');
		$this->template->content = View::factory('blog/admin/subcategory/form')
			->set('legend', __('Create Subcategory'))
			->set('submit', __('Create'))
			->bind('subcategory', $subcategory)
			->bind('errors', $errors);

		$subcategory = Sprig::factory('subcategory')->values($_POST);

		if ($_POST)
		{
			try
			{
				$subcategory->create();

				Message::instance()->info('The subcategory, :name, has been created.',
					array(':name' => $subcategory->name));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list')) );
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('admin');
			}
		}
	}

	/**
	 * Edit category details
	 */
	public function action_edit() {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Controller_Admin_Subcategory::action_edit');
		$this->template->content = View::factory('blog/admin/subcategory/form')
			->set('legend', __('Modify Subcategory'))
			->set('submit', __('Save'))
			->bind('subcategory', $this->_resource)
			->bind('errors', $errors);

		// Bind locally
		$subcategory = & $this->_resource;

		if ($_POST)
		{
			$subcategory->values($_POST);

			try
			{
				$subcategory->update();

				Message::instance()->info('The subcategory, :name, has been modified.',
					array(':name' => $subcategory->name));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list', 'id'=>NULL)) );
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('admin');
			}
		}
	}

	/**
	 * Delete a category
	 */
	public function action_delete() {
		Kohana::$log->add(Kohana::DEBUG, 'Executing Controller_Admin_Subcategory::action_delete');

		// Bind locally
		$subcategory = & $this->_resource;
		$name = $subcategory->name;

		if(Request::$is_ajax)
        {
            try
            {
				$subcategory->delete();
				$this->request->response = json_encode(
                    array('success' => TRUE, 'flash_class' => 'success', 'text'=>'The subcategory, '.$name.' has been deleted.')
                ); //return a json encoded result
			}
			catch (Exception $e)
            {
				Kohana::$log->add(Kohana::ERROR, 'Error occured deleting subcategory, id='.$subcategory->id.', '.$e->getMessage());
                $this->request->response = json_encode(
                    array('success' => FALSE, 'flash_class' => "error", 'text'=> 'An error occured deleting subcategory,'.$name)
                );
            }
            return; //end ajax
        }

		// If deletion is not desired, redirect to list
		if (isset($_POST['no']))
			$this->request->redirect( $this->request->uri(array('action'=>'list', 'id'=>NULL)) );

		$this->template->content = View::factory('blog/admin/subcategory/delete')
			->bind('subcategory', $this->_resource);

		// If deletion is confirmed
		if (isset($_POST['yes']))
		{
			try
			{
				$subcategory->delete();
				Message::instance()->info('The subcategory, :name, has been deleted.',
					array(':name' => $name));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list', 'id'=>NULL)) );
			}
			catch (Exception $e)
			{
				Kohana::$log->add(Kohana::ERROR, 'Error occured deleting subcategory, id='.$subcategory->id.', '.$e->getMessage());
				Message::instance()->error('An error occured deleting subcategory, :name.',
					array(':name' => $name));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list', 'id'=>NULL)) );
			}
		}
	}

}	// End of Controller_Admin_Subcategory

