<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Photo management controller
 *
 * @package		Blog
 * @category    Controller
 * @author		Nicolas AGIUS
 * @copyright	(c) 2011 Nicolas AGIUS
 * @license 	MIT
 */
class Controller_Admin_Photo extends Controller_Admin {

	protected $_resource = 'photo';

	protected $_acl_map = array(
		'new'     => 'create',
		'edit'    => 'edit',
		'crop'    => 'edit',
		'delete'  => 'delete',
		'default' => 'manage',
	);

	protected $_acl_required = 'all';

	protected $_view_map = array(
		'list'    => 'admin/layout/wide_column_with_menu',
		'crop'    => 'admin/layout/narrow_column',
		'default' => 'admin/layout/narrow_column_with_menu',
	);

	protected $_view_menu_map = array(
		'list'    => 'admin/photo/menu/list',
		// 'default' is _menu()
	);

	protected $_resource_required = array('edit','crop','delete');

	protected $_current_nav = 'admin/photo';

	/**
	 * Generate default context box
	 */
	protected function _menu() {
		return View::factory('admin/photo/menu/default');
	} 

	/**
	 * Load the specified category
	 */
	protected function _load_resource() {
		$id = $this->request->param('id', 0);
		$this->_resource = Sprig::factory('photo', array('id'=>$id))->load();
		if ( ! $this->_resource->loaded())
			throw new Kohana_Exception('That photo does not exist.', NULL, 404);
	}

	/**
	 * Redirect index action to list
	 */
	public function action_index() {
		$this->request->redirect( $this->request->uri(
			array('action' => 'list')), 301);
	}

	/**
	 * Display list of photos
	 */
	public function action_list() {
		Kohana::$log->add(Kohana::DEBUG,'Executing Controller_Admin_Photo::action_list');

		// Build request
		$query = DB::select();

		if(isset($_POST['terms']))
		{
			$query->where('title','like',"%".$_POST['terms']."%");
			$query->or_where('subtitle','like',"%".$_POST['terms']."%");
		}

		$photos = Sprig::factory('photo')->load($query, FALSE);


		if(Request::$is_ajax)
		{
			// return a json encoded HTML table
            $this->request->response = json_encode(
				View::factory('admin/photo/list_tbody')
					->bind('photos', $photos)
                    ->render()
            );
		}
		else
		{
			// return the full page
			$this->template->content = View::factory('admin/photo/list')
				->set('tbody', View::factory('admin/photo/list_tbody')
					->bind('photos', $photos)
				);
		}
	}

	/**
	 * Create a new photo
	 */
	public function action_new() {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Controller_Admin_Photo::action_new');
		$this->template->content = View::factory('admin/photo/form')
			->set('legend', __('Upload a new photo'))
			->set('submit', __('upload'))
			->bind('photo', $photo)
			->bind('errors', $errors);

		$photo = Sprig::factory('photo')->values(array_merge($_POST, $_FILES));

		if ($_POST && $_FILES)
		{

			try
			{
				$photo->create();

				Message::instance()->info('The photo, :title, has been created.',
					array(':title' => $photo->title));

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
	 * Edit photo details
	 */
	public function action_edit() {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Controller_Admin_Photo::action_edit');
		$this->template->content = View::factory('admin/photo/edit')
			->set('legend', __('Modify Photo'))
			->set('submit', __('Save'))
			->bind('photo', $this->_resource)
			->bind('errors', $errors);

		// Bind locally
		$photo = & $this->_resource;

		if ($_POST)
		{
			$photo->values($_POST);

			try
			{
				$photo->update();

				Message::instance()->info('The photo, :name, has been modified.',
					array(':name' => $photo->title));

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
	 * Crop photo details
	 */
	public function action_crop() {
		Kohana::$log->add(Kohana::DEBUG,
			'Executing Controller_Admin_Photo::action_crop');
		$this->template->content = View::factory('admin/photo/crop')
			->set('legend', __('Crop Photo'))
			->set('submit', __('Crop'))
			->bind('img', $img) 
			->bind('photo', $this->_resource)
			->bind('errors', $errors);

		// Bind locally
		$photo = & $this->_resource;

		$img=Image::factory($photo->path); 

		if ($_POST)
		{
            $img->crop($_POST['w'],$_POST['h'],$_POST['x'],$_POST['y']);
            $img->resize(100,100,Image::NONE);	//TODO put this in a config file
            $img->save();

			$this->request->redirect( $this->request->uri(array('action'=>'list', 'id'=>NULL)) );
		}

        // Set template scripts and styles
        $this->template->scripts[] = Route::get('media')->uri(array('file'=>'js/jquery.Jcrop.min.js'));
        $this->template->styles[Route::get('media')->uri(array('file'=>'css/jquery.Jcrop.css'))] = 'screen';

	}

	/**
	 * Delete a photo
	 */
	public function action_delete() {
		Kohana::$log->add(Kohana::DEBUG,'Executing Controller_Admin_Photo::action_delete');

		// Bind locally
		$photo = & $this->_resource;
		$name = $photo->title;

		if(Request::$is_ajax)
		{
			try
			{
				$photo->delete();
				$this->request->response = json_encode(
                	array('success' => TRUE, 'flash_class' => 'success', 'text'=>'The photo, '.$name.' has been deleted.')
      	    	); //return a json encoded result

			}
			catch (Exception $e)
			{
				Kohana::$log->add(Kohana::ERROR, 'Error occured deleting photo, id='.$photo->id.', '.$e->getMessage());
				$this->request->response = json_encode(
                	array('success' => FALSE, 'flash_class' => "error", 'text'=> 'An error occured deleting photo,'.$name)
      	    	); 

			}
			return; //end ajax
		}

		// If deletion is not desired, redirect to list
		if (isset($_POST['no']))
			$this->request->redirect( $this->request->uri(array('action'=>'list', 'id'=>NULL)) );

		$this->template->content = View::factory('admin/photo/delete')
			->bind('photo', $this->_resource);

		// If deletion is confirmed
		if (isset($_POST['yes']))
		{
			try
			{
				$photo->delete();
				Message::instance()->info('The photo, :name, has been deleted.',
					array(':name' => $name));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list', 'id'=>NULL)) );
			}
			catch (Exception $e)
			{
				Kohana::$log->add(Kohana::ERROR, 'Error occured deleting photo, id='.$photo->id.', '.$e->getMessage());
				Message::instance()->error('An error occured deleting photo, :name.',
					array(':name' => $name));

				if ( ! $this->_internal)
					$this->request->redirect( $this->request->uri(array('action'=>'list', 'id'=>NULL)) );
			}
		}
	}

}	// End of Controller_Admin_Photo

